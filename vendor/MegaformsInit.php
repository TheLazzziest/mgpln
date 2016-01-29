<?php
namespace Vendor;

use Vendor\Tools\Helpers;
use Vendor\Db\Migration;

defined("ABSPATH") or die("I'm only the wp plugin");

class MegaformsInit{

    private static $plugin_name; // plugin name or directory path to a plugin

    public static function activate($plugin_parent = "")
    {
        self::$plugin_name = Helpers::rltrim($plugin_parent);

        if(!empty(self::$plugin_name))
            self::checkPlugin();

        $db_migrator = new Migration();


        $result = $db_migrator->createTable('megaplan_key',[
            'id' => 'INT (6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'user' => 'VARCAHR(255) NOT NULL', // username for megaplan api
            'password' => 'VARCHAR(255) NOT NULL', // password for megaplan api
            'apikey' => 'VARCHAR (30) NOT NULL', // access key to megaplan api
        ]);

        if(!$result)
            throw new \Exception("Table hasn't been created");


        return $result;
    }

    public static function deactivate()
    {
        $db_migrator = new Migration();

        $result = $db_migrator->dropTable('megaplan_key');

        if(!$result)
            throw new \Exception("Table hasn't been dropped");

        return $result;
    }

    private static function checkPlugin()
    {
        $checker = new Tools\PluginChecker(self::$plugin_name);
        if($checker->is_installed())
            throw new \Exception( __('I can\'t find' . self::$plugin_name . ' plugin in your wp', 'megaplan-wp-plugin' ));

    }

    }
?>
