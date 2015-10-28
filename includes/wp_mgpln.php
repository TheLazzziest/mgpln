<?php
    class WP_MGPLN{
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
        public __construct($name = 'Megaplan plugin', $version = 'beta'){
            $this->version = $version;
            $this->plugin_name = $name;
        }

        protected function load_dependecies(){

        }
    }
?>
