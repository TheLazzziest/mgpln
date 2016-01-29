<?php
/*
	Plugin Name: Megaforms
	Plugin URI: https://github.com/TheLazzziest/mgpln
    Description: Easy create form with a simple interface
    Version: beta
    Author:
    License: GPL2
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Text Domain: megaforms
    Domain Path: /vendor/languages

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

add_action('admin_notices',['MegaformsBootstrap','admin_notice']);
register_activation_hook(__FILE__, ['MegaformsBootstrap','activator']);
register_deactivation_hook(__FILE__, ['MegaformsBootstrap','deactivator']);


class MegaformsBootstrap
{

    protected static $instance;

    //Autoload class for bootstrap component
    public static function autoload($class)
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
        $file = strval(str_replace("\0","",$file.".php")); // @TODO: find out better solution for file path
        if(file_exists($file) && is_readable($file))
            require_once $file;
        else
            throw new Exception("File $file doesn't exists");
    }

    public static function activator()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');
        try{
            if(!current_user_can('activate_plugins'))
                wp_die("User can't activate plugins");
            \Vendor\MegaformsInit::activate();
        }catch(\Exception $error){
            \Vendor\Tools\Helpers::handle_exception($error);
        }
    }

    public static function deactivator()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');
        try{
            if(!current_user_can('activate_plugins')) //@TODO: check for better condition for deactivation
                die("User can't deactivate plugins");
            \Vendor\MegaformsInit::deactivate();
        }catch(\Exception $error){
            \Vendor\Tools\Helpers::handle_exception($error);
        }
    }


    public static function bootstrap()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');
        try{
            if(empty(self::$instance)){
                self::$instance = new \Vendor\Plugin();
            }

            self::$instance->run();

        }catch(Exception $error){
            \Vendor\Tools\Helpers::handle_exception($error);
        }finally{
            die(__("System error occured. Apply to plugin developers","megaforms"));
        }
    }
}

define('MEGAFORMS_BOOTSTRAPPED', TRUE);
MegaformsBootstrap::bootstrap();
