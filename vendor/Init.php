<?php
namespace Vendor;

    class Init{

        // https://codex.wordpress.org/Function_Reference/is_plugin_active
        private static $plugin_name; // plugin name or directory path to a plugin

        public static function activate($plugin_parent = "")
        {
            self::$plugin_name = trim($plugin_parent);
            if(!empty(self::$plugin_name))
                self::checkPlugin();
            $db_migrator = new Db\Migration();

            $result = $db_migrator->createTable('megaplan_key',[
               'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'uid' => 'INT(6) UNSIGNED', //user id in wp
                'key' => "VARCHAR(30) NOT NULL", // access key to megaplan api
            ]);

        }

        public static function disactivate()
        {
            $db_migrator = new Db\Migration();
            $db_migrator->dropTable('megaplan');
        }

        private static function checkPlugin()
        {
            $checker = new Tools\PluginChecker(self::$plugin_name);
            if($checker->is_installed())
                throw new WP_Error('missing', self::$plugin_parent . ' hasn\'t been installed' , __('I can\'t find' . self::$plugin_parent . ' plugin in your wp', 'megaplan-wp-plugin' ));

        }

    }
?>
