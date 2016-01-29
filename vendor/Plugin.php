<?php
namespace Vendor;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the WP plugin");

    final class Plugin{
        /*
         * The current version of the plugin.
         * @access   protected
         * @var      string    $version    The current version of the plugin.
        */
        public $version;
        /*
        * The unique identifier of this plugin.
        * @access   protected
	    * @var      string    $plugin_name    The string is  used to uniquely identify this plugin.
        */
        public $plugin_name;
        /*
        *   The registry container to store all objects of the plugin
        *   @access protected
        *   @var    Registry    $registy    Container for storing all objects of the plugin
        */
        protected $_registry;

        /*
        * MGPLN costructor
        * @access   public
	    * @var      string    $name    The string used to uniquely identify this plugin.
        * @var      string    $version To identify the version of the plugin
        */
        public function __construct($name = 'Megaplan plugin', $version = 'beta')
        {
            $this->version = $version;
            $this->plugin_name = $name;
            $this->load_dependecies();
        }

        // Start plugin for Megaplan
        public function run()
        {
            $this->init_plugin_shortcuts();
            $this->init_admin_menu();
//            $this->registry['router']->start();
            die("RUN");

        }

        /*
        * set autoload function to pull all dependencies of the plugin
        * @access   private
        */
        private function load_dependecies()
        {
            if(empty($this->registry) || !($this->registry instanceof Core\Registry))
                $this->registry = new Core\Registry();

            $this->_registry['router'] = new Core\Router();
            $this->_registry['settings'] = new Tools\Settings();
            die('Settings');
        }

        private function init_admin_menu()
        {

//            $this->_registry['settings']->add_menu_page(
//                'Megaforms', 'Forms Manager', 'manage_options', 'mgfrms'
//            );
//            $this->_registry['settings']->init_menu_pages();
        }

        private function init_plugin_shortcuts()
        {

        }

//
//        //Set language for text
//        private function set_locale()
//        {
//            $plugin_i18n = new Core\I18n();
//            $plugin_i18n->set_domain($this->get_plugin_name());
//        }

    }
?>
