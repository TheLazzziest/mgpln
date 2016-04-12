<?php
namespace Megaforms\Vendor;


use Megaforms\Vendor\Core\Registry;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the WP plugin");

    final class Plugin{
        /*
         * The current version of the plugin.
         * @access   protected
         * @var      string    $version    The current version of the plugin.
         */
        public static $version;
        /*
         * The unique identifier of this plugin.
         * @access   protected
	     * @var      string    $plugin_name    The string is  used to uniquely identify this plugin.
         */
        public static $plugin_name;

        /*
         *   The full url path to the plugin
         *   @access protected
         *   @var    Registry    $registy    Container for storing all objects of the plugin
         */

        public static $plugin_url;

        /*
         *   The full real path to the plugin
         *   @access public
         *   @property    string    $plugin_path
         */
        public static $plugin_path;



        /*
        * MGPLN costructor
        * @access   public
	    * @var      string    $name    The string used to uniquely identify this plugin.
        * @var      string    $version To identify the version of the plugin
        */

        public function __construct($name = 'megaforms', $version = 'beta')
        {
            self::$version = $version;
            self::$plugin_name = $name;

            $this->load_dependecies();
            $this->set_locale();
        }

        /*
        * set autoload function to pull all dependencies of the plugin
        * @access   private
        */
        private function load_dependecies()
        {

            self::$plugin_path = plugin_dir_path(__DIR__);
            self::$plugin_url = plugin_dir_url(__DIR__);

            Registry::getInstance()->loader = new Core\Loader();
            Registry::getInstance()->router = new Core\Router();
            Registry::getInstance()->i18n = new Core\I18n(self::$plugin_name);

            Registry::getInstance()->settings = new Libs\Wpapi\Settings();
//            Registry::getInstance()->shortcuts = new Libs\Shortcuts();


        }

        //Set language for text
        private function set_locale()
        {
            Registry::getInstance()->loader->add_action(
                'plugins_loaded',
                Registry::getInstance()->i18n,
                'load_plugin_textdomain'
            );
        }

        // Start plugin for Megaplan
        public function run()
        {
            Registry::getInstance()->loader->run();
            Registry::getInstance()->router->start();
        }

    }
?>
