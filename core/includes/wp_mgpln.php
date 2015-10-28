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
            $this->load_dependencies();
            $this->set_locale();
        }

        private function load_dependecies(){
            /**
            * The class responsible for orchestrating the actions and filters of the
            * core plugin.
            */
            require_once WP_MGPLN_INCLUDES . 'includes/wp_mgpln_loader.php';

            /**
            * The class responsible for defining internationalization functionality
            * of the plugin.
            */
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wp_mgpln_i18n.php';

            /**
	          * The class responsible for defining all actions that occur in the admin area.
            */
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wp_mgpln_admin.php';
            /**
		      * The class responsible for defining all actions that occur in the public-facing
		      * side of the site.
		      */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mgpln-public.php';
            $this->loader = new WP_MGPLN_LOADER();

        }

        private function set_locale(){
            $plugin_i18n = new WP_MGPLN_I18N();
        }
    }
?>
