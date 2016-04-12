<?php
namespace Megaforms\Vendor;

use Megaforms\Vendor\Exceptions\MigrationException;
use Megaforms\Vendor\Libs\Helpers\CommonHelpers;
use Megaforms\Vendor\Db\PluginMigration;

defined("ABSPATH") or die("I'm only the wp plugin");

class Init{

    private static $plugin_name; // plugin name or directory path to a plugin

    /**
     * @param string $plugin_parent
     * @throws \Exception
     */
    public static function activate($plugin_parent = "")
    {
        self::$plugin_name = CommonHelpers::strim($plugin_parent);

        if(!empty(self::$plugin_name)){
            self::checkPlugin();
        }

        $migrator = new PluginMigration();
        $migrator->up();
    }

    /**
     *
     */
    public static function deactivate()
    {
//        $migrator = new PluginMigration();
//
//        $migrator->down();
    }

    /**
     * @throws \Exception
     */
    private static function checkPlugin()
    {
        $checker = new Libs\PluginChecker(self::$plugin_name);
        if($checker->is_installed()) {
            throw new MigrationException(
                __('I can\'t find' . self::$plugin_name . ' plugin in your wp', 'megaforms' )
            );
        }
    }

    }
?>
