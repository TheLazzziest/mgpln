<?php
/**
 *  Plugin Name: Megaforms
 *  Plugin URI: https://github.com/TheLazzziest/mgpln
 *  Description: Easy create form with a simple interface
 *  Version: beta
 *  Author: Max Yakovenko <mxyakovenko9@gmail.com>
 *  License: GPL2
 *  License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *  Text Domain: megaforms
 *  Domain Path: /vendor/languages
 *
 *  {Plugin Name} is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  any later version.
 *
 *  {Plugin Name} is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with {Plugin Name}. If not, see {License URI}.

 *  PHP version 5

 * Main bootstrap class
 *
 * @category Plugin
 * @package  Megaforms
 * @author   Max Yakovenko <mxyakovenko9@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @version  GIT: https://github.com/TheLazzziest/mgpln
 * @link     https://github.com/TheLazzziest/mgpln
 * @since    Class available since Release beta
 */


// Temporary solution for plugin bootstrap security

defined("WPINC") or die("I'm only the WP plugin");

final class MegaformsBootstrap
{

    private static $_parentNs = 'Megaforms';

    //Autoload class for bootstrap component
    // One VERY IMPORTANT thing, when an autoload function is used in wp plugin
    // it must check that a file is refer to the namespace of the current plugin

    /**
     *
     * @param $class string Filename of including class
     *
     * @throws \Exception
     * @return null
     *
     */
    public static function autoload($class)
    {
        $file = __DIR__;
        $class = str_replace("\\", "/", $class);
        $nsParts = explode("/", $class);
        if (!strcmp($nsParts[0], self::$_parentNs)) {

            // throw away parentNs from directory path in order to omit its double occurence
            array_shift($nsParts);
            $file = sprintf("%s/%s.php", $file, implode("/", $nsParts));
            if (file_exists($file) && is_readable($file)) {
                include_once $file;
            } else {
                throw new Megaforms\Vendor\Exceptions\MegaformsException("File $file doesn't exists");
            }
        }
    }

    /**

     * Activation function of the plugin
     * @return null
     *
     */
    public static function enable()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');

        try{
            // Error based on capabilities.php
            // Topic : https://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_get_current_user-4

//            if (!current_user_can('activate_plugins')) {
//
//                throw new \Exception("User can't activate plugins");
//            }
//            \Megaforms\Vendor\Init::activate();
        }catch(\Exception $error){
            Megaforms\Vendor\Libs\Helpers\CommonHelpers::handle_exception($error);
        }
    }

    /**

     * Disabling function of a plugin
     * @return null
     */
    public static function disable()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');
        try
        {
            // Error based on capabilities.php
            // Topic : https://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_get_current_user-4

            //@TODO: check condition for deactivation
//            if (!current_user_can('activate_plugins')) {
//
//                throw new \Exception("User can't deactivate plugins");
//            }
//            \Megaforms\Vendor\Init::deactivate();
        }catch(Megaforms\Vendor\Exceptions\MegaformsException $error){
            Megaforms\Vendor\Libs\Helpers\CommonHelpers::handle_exception($error);
        }
    }

    public static function boot()
    {
        spl_autoload_register('MegaformsBootstrap::autoload');

        try
        {
            // Error based on capabilities.php
            // Topic : https://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_get_current_user-4

//            if ( ! current_user_can( 'activate_plugins' ) ){
//                wp_die('User can\'t activate plugins');
//            }
            $thePlugin = new Megaforms\Vendor\Plugin();
            $thePlugin->run();
        }catch(Megaforms\Vendor\Exceptions\MegaformsException $error){
            $error = Megaforms\Vendor\Libs\Helpers\CommonHelpers::handle_exception($error);
            MegaformsBootstrap::admin_notice($error);
        }
    }

    /**

     * Main entry point of the plugin
     * @return Megaforms\Vendor\Plugin
     *
     */

    public static function admin_notice($message){
        return "<div><p>" . _e(esc_html($message)) . "</p></div>";
    }
}

// add action notice in admin notice bar
add_action('admin_notices', 'MegaformsBootstrap::admin_notice');
register_activation_hook(__FILE__, 'MegaformsBootstrap::enable');
register_deactivation_hook(__FILE__, 'MegaformsBootstrap::disable');

define('MEGAFORMS_BOOTSTRAPPED', true);
MegaformsBootstrap::boot();

