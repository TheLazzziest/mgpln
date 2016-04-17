<?php
namespace Megaforms\Vendor;

use Megaforms\Vendor\Exceptions\LibsException;
use Megaforms\Vendor\Libs\Helpers\CommonHelpers;
use Megaforms\Vendor\Core\PluginMigration;
use Megaforms\Vendor\Libs\Traits\Registry;

defined("ABSPATH") or die("I'm only the wp plugin");

/**
 * Activating/Deactivating plugin
 * Class Init
 * @package Megaforms\Vendor
 */
class Init{

    /**
     * Plugin name or directory path to a parent plugin
     * @property string $parent_plugin
     */
    private $parent_plugin;

    /**
     * Method start plugin installation
     * @param string $plugin_parent
     * @throws \Exception
     */
    private function __construct($parent_plugin = ''){
        @$this->$parent_plugin = $parent_plugin;
    }
    public static function activate($plugin_parent = "")
    {
        $init = new self(CommonHelpers::strim($plugin_parent));

        if(!empty($init->parent_plugin)){
            $init->checkPlugin();
        }

        PluginMigration::load()->up();
    }

    /**
     *  Method starts plugin disabling, NOT REMOVING !!!!
     */
    public static function deactivate()
    {
        PluginMigration::load()->down();
    }

    /**
     * @throws \Exception
     */
    public function checkPlugin()
    {
        $checker = new Libs\Wpapi\PluginChecker($this->parent_plugin);
        if($checker->is_installed()) {
            throw new LibsException(
                LibsException::MISSING_PARENT_PLUGIN,
                [$this->parent_plugin]
            );
        }
    }

    }
?>
