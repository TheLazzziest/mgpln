<?php
namespace Megaforms\Vendor\Libs;

class PluginChecker {

    private $plugin_path;

    public function __construct($plugin_path){

        if(empty(Helpers::rltrim($plugin_path)))
            die('Plugin path is empty');
        $this->plugin_path = $plugin_path;
    }

    // https://codex.wordpress.org/Function_Reference/is_plugin_active
    public function is_installed()
    {
        if (!function_exists('is_plugin_active'))
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        return is_plugin_active($this->plugin_path);
    }
}
?>
