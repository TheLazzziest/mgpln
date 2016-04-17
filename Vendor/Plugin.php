<?php
namespace Megaforms\Vendor;


use Megaforms\Vendor\Libs\Helpers\CommonHelpers;
use Megaforms\Vendor\Libs\Wpapi\I18n;
use Megaforms\Vendor\Libs\Wpapi\Loader;
use Megaforms\Vendor\Libs\Helpers\PluginDataSet;
use Megaforms\Vendor\Core\Router;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the WP plugin");

/**
 * Class Plugin
 * @package Megaforms\Vendor
 */
final class Plugin{


    /**
     * MGPLN costructor
     * @access   public
     * @param    string    $name    The string used to uniquely identify this plugin.
     * @param    string    $version To identify the version of the plugin
     */
    public function __construct($name = 'megaforms', $version = 'beta')
        {
            PluginDataSet::load()->version = CommonHelpers::strim($version);
            PluginDataSet::load()->plugin_name = CommonHelpers::strim($name);

            PluginDataSet::load()->plugin_path = plugin_dir_path(__DIR__);
            PluginDataSet::load()->plugin_url = plugin_dir_url(__DIR__);
            PluginDataSet::load()->vendor_path = PluginDataSet::load()->plugin_path.'Vendor/';
            PluginDataSet::load()->libs_path = PluginDataSet::load()->vendor_path .'Libs/';
            PluginDataSet::load()->views_path = PluginDataSet::load()->vendor_path . 'Views/';
            PluginDataSet::load()->uploads_path = PluginDataSet::load()->vendor_path . 'data/.uploads/';
            /** object PluginDataSet */
            PluginDataSet::load()->checkDependencies();
            I18n::load()->setUp();

        }


    /**
     * Main run function
     */
    public function run()
        {   /** @var Router */
            Router::load()->start();
            Loader::load()->run();
        }

    }
?>
