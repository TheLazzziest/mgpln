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
defined("ABSPATH") or die("I'm only the WP plugin");

//Basic debug WP tool
define("WP_DEBUG", true);

add_action('admin_notices',['Mgpln_Bootstrap','admin_notice']);
spl_autoload_register('Mgpln_Bootstrap::_autoload');
register_activation_hook(__FILE__, ['Mgpln_Bootstrap','_activator']);
register_deactivation_hook(__FILE__, ['Mgpln_Bootstrap','_deactivator']);


class Mgpln_Bootstrap
{

    protected static $instance;

    private function __construct()
    {
        self::$instance = new \Vendor\Wp_mgpln();
    }
    //Autoload class for bootstrap component
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
        die($file);
        $file = strval(str_replace("\0","",$file.".php")); // @TODO: find out better solution for file path
        if(file_exists($file) && is_readable($file))
            require_once $file;
        else
            throw new WP_Error(500, var_dump($file) , "File $file doesn't exists");
    }

    public static function _activator()
    {
        try{
            if(!current_user_can('activate_plugins'))
                throw new Wp_Error('violation', "You can't activate the plugin");
            if(get_option('mgpln_activated') === FALSE &&\Vendor\Mgpln_Init::activate()){
                    add_option('mgpln_activated', TRUE);
            }
        }catch(WP_Error $error){
            Mgpln_Bootstrap::admin_notice($error->get_error_message(),"error");
        }
    }

    public static function _deactivator()
    {
        try{
            if(!current_user_can('deactivate_plugins'))
                throw new WP_Error('violation', "You don't have proper permision","Try to ask admin to deactivate plugin");
            if(get_option('mgpln_activated') === false)
                throw new WP_Error('violation',"Plugin is turned off",var_dump("Try to install the plugin"));
            if(\Vendor\Mgpln_Init::deactivate())
                delete_option('mgpln_activated');
        }catch(WP_Error $error){
            Mgpln_Bootstrap::admin_notice($error->get_error_message(),"error");
        }
    }


    public static function bootstrap()
    {
        try{
            if(get_option('mgpln_activated'))
                throw new WP_Error('violation','Try to install plugin',var_dump('Try to install the plugin'));
            add_option('mgpln_bootstrapped',TRUE,TRUE);
            if(empty(self::$instance)){
               self::$instance = new self;
            }

            self::$instance->run();

        }catch(WP_Error $e){
            Mgpln_Bootstrap::admin_notice($error->get_error_message(),"error");
        }catch(Exception $e){
            Mgpln_Bootstrap::admin_notice($error->getMessage(),"error");
        }finally{
            Mgpln_Bootstrap::admin_notice("Unknown error","error");
        }
    }

    public static function admin_notice($message,$class)
    {
        return "<div class=\"$class\""._e($message,'my-text-domain')."</div>";
    }
}

Mgpln_Bootstrap::bootstrap();
