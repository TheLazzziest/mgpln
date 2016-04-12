<?php
namespace Megaforms\Vendor\Libs\Wpapi;

use Megaforms\Vendor\Exceptions\MegaformsException;
use Megaforms\Vendor\Libs\Helpers\CommonHelpers;
use Megaforms\Vendor\Plugin;

class PluginChecker {

    private $_plugin_path;

    public function __construct($plugin_path){

        if(!is_string($plugin_path) && empty(CommonHelpers::strim($plugin_path))){
            throw new MegaformsException(
                __('Inproper type of parameter', Plugin::$plugin_name)
            );
        }
        $this->_plugin_path = $plugin_path;
    }

    // https://codex.wordpress.org/Function_Reference/is_plugin_active
    public function is_installed()
    {
        return is_plugin_active($this->_plugin_path);
    }
}
?>
