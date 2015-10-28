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

define( 'WP_MGPLN_PLUGIN', __FILE__);
define( 'WP_MGPLN_PATH', plugin_dir_path(WP_MGPLN_PLUGIN));
define( 'WP_MGPLN_CORE', WP_MGPLN_PATH . '/core/');
define( 'WP_MGPLN_INCLUDES', WP_MGPLN_CORE . 'includes/');

function wp_mgpln_autoload(){
    require_once WP_MGPLN_INCLUDES . 'wp_mgpln_init.php';
    require_once WP_MGPLN_INCLUDES . 'wp_mgpln.php';
    define('WP_INIT_OK', true);
}

function wp_mgpln_activator(){
    spl_autoload_register('wp_mgpln_autoload', true);
    try{
        WP_MGPLN_INIT::activate();
    }catch(WP_Error $error){
        add_action('admin_notices', 'wp_mgpln_admin_notice', $error->get_error_message());
    }
    run();
}

function wp_mgpln_admin_notice($message){
    $notice = '';
    foreach($message as $label => $text){
        $notice .= $label . ': ' . $text . '\n';
    }
    echo "<div class=\"\">" . $notice . "</div>";
}


function wp_mgpln_deactivator(){
    if(!defined(WP_INIT_OK))
        spl_autoload_register('wp_mgpln_autoload');
    try{
       WP_MGPLN_INIT::deactivate();
    }catch(WP_Error $error){
        add_action('admin_notices', 'wp_mgpln_admin_notice', $error->get_error_message());
    }
}

function run(){
    $mgpln = new wp_mgpln();
    $mgpln->run();
}

register_activation_hook(__FILE__, 'wp_mgpln_activate');
register_deactivation_hook(__FILE__, 'wp_mgpln_deactivate');
