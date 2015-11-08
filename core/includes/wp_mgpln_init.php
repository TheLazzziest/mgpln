<?php
namespace Core\Mgplninit;

    class WP_MGPLN_INIT{
        // https://codex.wordpress.org/Function_Reference/is_plugin_active
        private static $plugin_name; // plugin name or directory path to a plugin

        public static function activate($plugin_parent = null)
        {
            self::plugin_name = $plugin_parent;
            if(!empty(trim(self::plugin_name)))
                self::checkPlugin();
        }

        public static function disactivate()
        {

        }

        private static function checkPlugin()
        {
            $checker = new PluginChecker(self::plugin_name);
            if($checker->is_installed())
                throw new WP_Error('missing', $plugin_parent . ' hasn\'t been installed' , __('I can\'t find' . $plugin_parent . ' plugin in your wp', 'megaplan-wp-plugin' ));

        }

    }
?>
