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

// Temporary solution for plugin bootstrap security
defined("ABSPATH") or die("Can't find WP root directory");

//Basic debug WP tool
define( 'WP_DEBUG', true);

spl_autoload_register('Mgpln_Bootstrap::_autoload');
register_activation_hook(__FILE__, ['Mgpln_Bootstrap','_activator']);
register_deactivation_hook(__FILE__, ['Mgpln_Bootstrap','_deactivator']);

add_action('plugins_loaded',['Mgpln_Bootstrap','run']);

class Mgpln_Bootstrap
{
    protected static $instance;
    public $plugin;

    private function __construct(){
        $this->plugin = new \Vendor\Wp_mgpln();
        $this->plugin->run();
    }
    public static function _autoload($class)
    {
        $DS = DIRECTORY_SEPARATOR;
        $file = dirname(__FILE__);
        $class = str_replace("\\","/", $class);
        $nsParts = explode('/',$class);
        $nsLength = count($nsParts);
        foreach($nsParts as $key => $path){
            $file .= $DS;
            $file .= ($key < $nsLength-1) ? strtolower($path) : $path;
        }
        $file = strval(str_replace("\0","",$file.".php")); // @todo find out better solution for file path
        if(file_exists($file) && is_readable($file))
            require_once $file;
        else
            throw new WP_ERROR(500, "File $file doesn't exists", var_dump($file));
    }

    public static function _activator(){
        try{
            \Vendor\Init::activate();
            define("MGPLN_BOOTSTRAP", TRUE);
        }catch(WP_Error $error){
            Mgpln_Bootstrap::admin_notice($error->get_error_message());
        }
    }

    public static function _deactivator(){
        try{
            if(!defined("MGPLN_BOOTSTRAP"))
                throw new WP_Error('violation',"Can't uninstal the plugin");
           \Vendor\Init::deactivate();
            define("MGPLN_BOOTSTRAP", NULL);
        }catch(WP_Error $error){
            Mgpln_Bootstrap::admin_notice($error->get_error_message());
        }
    }


    public static function run()
    {
        if(!current_user_can('activate_plugins'))
            throw new Wp_Error('');
        if(!isset(self::$instance)){
           self::$instance = new self;
        }
        return self::$instance;
    }

    public static function admin_notice($message)
    {
        wp_die("<h2>$message</h2>");
    }
}
