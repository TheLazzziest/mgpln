<?php
    class WP_MGPLN_INIT{
        public static function activate($plugin_parent = 'contact-form-7')
        {
            if (!function_exists('get_plugins'))
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            $pluginList = get_plugins();
            if (!self::is_installed($plugin_parent, $pluginList))
                throw new WP_Error('missing', $plugin_parent . ' hasn\'t been installed' , __('I can\'t find' . $plugin_parent . ' plugin in your wp', 'megaplan-wp-plugin' ));
            if (!is_plugin_active('wpcf7_contact_form'))
                throw new WP_Error('inactive', $plugin_parent. ' must be activated', __('Turn on your '. $plugin_parent . ' plugin', 'megaplan-wp-plugin'));
        }

        public static function disactivate()
        {

        }

        private static function is_installed($plugin_name, $plugin_list = array())
        {
            foreach($plugin_list as $plugin){
                if(!strcmp($plugin_name, $plugin['TextDomain']))
                    return true;
            }
            return false;
        }
    }
?>
