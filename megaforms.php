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

    private static $parentNs = 'Megaforms';

    //Autoload class for bootstrap component
    // One VERY IMPORTANT thing, when an autoload function is used in wp plugin
    // it must check that a file is reffer to namespace of the current plugin

    public static function autoload($class)
    {
        $file = dirname(__FILE__);
        $class = str_replace("\\","/", $class);
        $nsParts = explode('/',$class);
        if(!strcmp($nsParts[0],self::$parentNs)) {
            array_shift($nsParts);
            $file = sprintf("%s/%s.php",$file,implode('/',$nsParts));
            if(file_exists($file) && is_readable($file))
                require_once $file;
            else
                throw new \Exception("File $file doesn't exists");
        }
    }

    public static function activator()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');
        try{
            if(!current_user_can('activate_plugins'))
                throw new \Exception("User can't activate plugins");
            \Megaforms\Vendor\MegaformsInit::activate();
        }catch(\Exception $error){
            \Megaforms\Vendor\Libs\Helpers::handle_exception($error);
        }
    }

    public static function deactivator()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');
        try{
            if(!current_user_can('activate_plugins')) //@TODO: check condition for deactivation
                throw new \Exception("User can't deactivate plugins");
            \Megaforms\Vendor\MegaformsInit::deactivate();
        }catch(\Exception $error){
            \Megaforms\Vendor\Libs\Helpers::handle_exception($error);
        }
    }


    public static function bootstrap()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');
        try{
            if(empty(self::$instance)){
                self::$instance = new \Megaforms\Vendor\Plugin();
            }

            self::$instance->run();

        }catch(Exception $error){
            \Megaforms\Vendor\Libs\Helpers::handle_exception($error);
        }finally{ // in order to see server logs concerning some system error, comment finally block
            die(__("System error occured. Apply to plugin developers","megaforms"));
        }
    }
}

define('MEGAFORMS_BOOTSTRAPPED', TRUE);
MegaformsBootstrap::bootstrap();
