<?php
/*
	Plugin Name: Megaplan WP Plugin
	Plugin URI: https://github.com/TheLazzziest/mgpln
    Description: Megaplan WP plugin
    Version: beta
    Author: <a href="http://sadesign.pro">Sadesign Studio</a>
    License: GPL2
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Text Domain: megaplan-wp-plugin
    Domain Path: /languages

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

//Basic debug WP tool
define( 'WP_DEBUG', true);

define( 'WP_MGPLN_PLUGIN', __FILE__);
define( "DS", DIRECTORY_SEPARATOR);

function formPath($filePath = WP_MGPLN_PLUGIN){
    $path = plugin_dir_path($filePath);
    $path[strlen($path)-1] = "\0";
    return $path;
}

define( 'WP_MGPLN_PATH', formPath());

function wp_mgpln_autoload($class)
{
    $file = WP_MGPLN_PATH;
    $class = str_replace("\\","/", $class);
    $nsParts = explode('/',$class);
    $nsLength = count($nsParts);
    foreach($nsParts as $key => $path){
        $file .= DS;
        $file .= ($key < $nsLength-1) ? strtolower($path) : $path;
    }
    $file = strval(str_replace("\0","",$file.".php")); // @todo find out better solution for file path
    if(file_exists($file) && is_readable($file))
        require_once $file;
    else
        throw new WP_ERROR(500, "File $file doesn't exists", var_dump($file));
}

function run(){
    $mgpln = new \Vendor\WP_MGPLN();
    $mgpln->run();
}

function wp_mgpln_activator(){
    try{
        spl_autoload_register('wp_mgpln_autoload');
        \Vendor\Init::activate();
    }catch(WP_Error $error){
        add_action('admin_notices', 'wp_mgpln_admin_notice', $error->get_error_message());
    }
}

function wp_mgpln_admin_notice($message){
    $notice = '';
    foreach($message as $label => $text){
        $notice .= $label . ': ' . $text . '\n';
    }?>
    <div class=""><?php _e($notice,'my-text-domain' )?></div>;
<?php
}

function wp_mgpln_deactivator(){
    try{
       Core\Includes\Init::deactivate();
    }catch(WP_Error $error){
        add_action('admin_notices', 'wp_mgpln_admin_notice', $error->get_error_message());
    }
}

register_activation_hook(__FILE__, 'wp_mgpln_activator');
register_deactivation_hook(__FILE__, 'wp_mgpln_deactivator');

run();
