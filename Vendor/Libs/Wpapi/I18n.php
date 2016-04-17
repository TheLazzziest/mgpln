<?php
namespace Megaforms\Vendor\Libs\Wpapi;

use Megaforms\Vendor\Libs\Helpers\PluginDataSet;
use Megaforms\Vendor\Libs\Traits\Registry;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

/**
 * Class I18n
 * @package Megaforms\Vendor\Libs\Wpapi
 */
final class I18n{

    use Registry;
        /**
         * The domain specified for this plugin.
         *
         * @access   private
         * @var      string    $domain    The domain identifier for this plugin.
         */
        private $_domain;


    /**
     *
     */
    public function __construct(){
            $this->_domain = PluginDataSet::load()->plugin_name;
    }

    public function setLocale(){
        load_plugin_textdomain(
            $this->_domain,
            false,
            plugin_dir_path(PluginDataSet::load()->plugin_path)
        );
    }

    public function setUp(){
        Loader::load()->add_action(
            'plugins_loaded',
            $this,
            'setLocale'
        );
    }
}
?>