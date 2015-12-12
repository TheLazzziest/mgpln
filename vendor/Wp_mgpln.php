<?php
namespace Vendor;

defined("BOOSTRAPED") or die("Direct access denied");

    class Wp_mgpln{
        /*
         * The current version of the plugin.
         * @access   protected
         * @var      string    $version    The current version of the plugin.
        */
        protected $version;
        /*
         * The loader that's responsible for maintaining and registering all hooks that power
	     * the plugin.
         * @access   protected
         * @var      Mgpln_Loader    $loader    Maintains and registers all hooks for the plugin.
        */
        protected $loader;
        /*
        * The unique identifier of this plugin.
        * @access   protected
	    * @var      string    $plugin_name    The string used to uniquely identify this plugin.
        */
        protected $plugin_name;
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
            $this->load_dependencies();
            $this->get_loader();
            $this->set_locale();

        }
        /*
        * class autoloader
        * @access   protected
	    * @var      string    $class    The string used to uniquely identify the required class.
        */

        // Start plugin for Megaplan
        public function run()
        {
            $this->loader->run();
            Core\Router::start();
        }

        /*
        * set autoload function to pull all dependencies of the plugin
        * @access   private
        */
        private function load_dependecies()
        {
            $this->loader = new Core\Loader();
        }

        //Set language for text
        private function set_locale()
        {
            $plugin_i18n = new Core\I18n();
            $plugin_i18n->set_domain($this->get_plugin_name());
            $this->loader->add_action("plugins_loaded", $plugin_i18n,"load_text_domain");
        }

        // Return plugin name
        protected function get_plugin_name()
        {
            return $this->plugin_name;
        }

        //Return Loader class
        protected function get_loader()
        {
            if(empty($this->loader))
                $this->loader = new \Vendor\Core\WP_MGPLN_LOADER();
            return $this->loader;
        }
    }
?>
