<?php
namespace Megaforms\Vendor\Libs\Wpapi;

use Megaforms\Vendor\Exceptions\ArgException;
use Megaforms\Vendor\Exceptions\LibsException;
use Megaforms\Vendor\Libs\Helpers\CommonHelpers;
use Megaforms\Vendor\Libs\Helpers\PluginDataSet;
use Megaforms\Vendor\Libs\Traits\Registry;
use Megaforms\Vendor\Plugin;

final class PluginChecker {

    use Registry;
    private $_plugin_path;

    public function __construct($plugin_path){

        if(!is_string($plugin_path) && empty(CommonHelpers::strim($plugin_path))){
            throw new ArgException(
                ArgException::INVALID_PARAMETER_TYPE,
                [__('Inproper type of parameter', PluginDataSet::load()->plugin_name)]
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
