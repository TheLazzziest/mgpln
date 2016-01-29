<?php
namespace Vendor\Core;
    class I18n{
        /**
         * The domain specified for this plugin.
         *
         * @access   private
         * @var      string    $domain    The domain identifier for this plugin.
         */
        private $domain;

        public function load_plugin_textdomain(){
            load_plugin_textdomain($this->domain,false,plugin_dir_path(dirname(__FILE__)));
        }
    }
?>