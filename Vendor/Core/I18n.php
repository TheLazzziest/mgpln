<?php
namespace Megaforms\Vendor\Core;

use Megaforms\Vendor\Libs\Helpers\CommonHelpers;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");
final class I18n{
        /**
         * The domain specified for this plugin.
         *
         * @access   private
         * @var      string    $domain    The domain identifier for this plugin.
         */
        private $_domain;

        public function __construct($domain){
            if( is_string($domain) === true
                && empty(CommonHelpers::strim($domain)) === false ){

                $this->_domain = $domain;
            }
        }
        public function load_plugin_textdomain(){
            load_plugin_textdomain(
                $this->_domain,
                false,
                plugin_dir_path(dirname(__FILE__))
            );
        }
    }
?>
