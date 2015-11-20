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

define( 'WP_MGPLN_PATH', plugin_dir_path(WP_MGPLN_PLUGIN));
define( 'WP_MGPLN_VENDOR', WP_MGPLN_PATH.DS.'vendor');

function wp_mgpln_autoload($class)
{
    $file = WP_MGPLN_PATH;
    $class = strreplace("\\","/", $class);
    $nsParts = explode('/',$class);
    if(strpos($nsParts[count($nsParst) - 1],"::")){
        $nsParts[] = explode("::",$nsParts[count($nsParst) - 1]);
        unset($nsParts[count($nsParst) - 1]);
    }
    foreach($nsParts as $filepath){
        $file .= DS.$filepath;
    }
    if(file_exists($file.".php"))
        require_once $file.".php";
    else
        throw WP_ERROR(500, "File $file doesn't exists", var_dump($file));
}

function run(){
    $mgpln = new \Vendor\WP_MGPLN();
    $mgpln->run();
}

function wp_mgpln_activator(){
    try{
        spl_autoload_register('wp_mgpln_autoload', true);
        \Vendor\Init::activate();
    }catch(WP_Error $error){
        add_action('admin_notices', 'wp_mgpln_admin_notice', $error->get_error_message());
    }
    run();
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

register_activation_hook(__FILE__, 'wp_mgpln_activate');
register_deactivation_hook(__FILE__, 'wp_mgpln_deactivate');
