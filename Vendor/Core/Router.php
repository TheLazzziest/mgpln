<?php
namespace Megaforms\Vendor\Core;

use Megaforms\Vendor\Exceptions\CoreException;
use Megaforms\Vendor\Libs\Settings;
use Megaforms\Vendor\Libs\Shortcuts;
use Megaforms\Vendor\Plugin;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

final class Router {
    /**
     * URL to http://site.domain/wp-admin/admin.php
     * @var string
     */
    protected $_is_admin_area;

    /** @var string */
    protected $_page = '';

    /** @var string */
    protected $_controller = '';

    /** @var string */
    protected $_action = '';

    /** @var string|NULL */
    protected $_param;

    /** @var bool */
    protected $_is_ajax = FALSE;

    /** @var string */
    protected $_ajax_prefix = 'wp_megaforms_ajax';

    /** @var Settings Api class */
    protected $_settings;

    /** @var Shortcuts Api class */
    protected $_shortcuts;

    /**
     * List of admin pages in megaforms plugin
     * @var array
     */
    protected $_admin_pages = [
        'PluginManager'
    ];

    /** @var  array list of Admin sub-pages */
    protected $_admin_sub_pages;

    public function __construct()
    {
        $this->_is_admin_area = is_admin();
        $this->_is_ajax = $this->isAjax();
        if($this->_is_admin_area){
            $this->parseRequest();
        }

        Registry::getInstance()->loader->add_action(
            'admin_menu',
            $this,
            'init_admin_settings'
        );
//        Plugin::$registry->loader->add_action(
//            'plugin_action_links_'.Plugin::$plugin_name,
//            $this
//        );
    }

    /**
     * Parse url params in order to form path the controller in admin folder
     */
    private function parseRequest()
    {
        // @TODO: fix page found condition
        $this->_page = empty($_REQUEST['page']) ? '' : $_REQUEST['page'];
        $this->_controller  = empty($_REQUEST['controller']) ? 'FormController' : $_REQUEST['controller'];
        $this->_action = empty($_REQUEST['action']) ? 'index' : $_REQUEST['action'];
        // @TODO: to finish params section after routing will be set up
        $this->_param = empty($_REQUEST['params']) ? '' : $_REQUEST['params'];
    }

    private function isAjax(){
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Form access path to particular page file in admin folder
     */
    private function formPathToAdmin(){
        $path = explode('/',Plugin::$plugin_path);
        $path[count($path)-1] = 'admin';
        $fullPath = sprintf('%s/%s.php', implode('/',$path),$this->_page);
        return $fullPath;
    }

    private function isPluginRoute(){
        return !empty($this->_page) &&
                array_key_exists($this->_page,$this->_admin_pages) &&
                !empty($this->_controller) &&
                !empty($this->_action);
    }

    public function init_admin_settings(){

        Registry::getInstance()->settings->add_admin_settings_page(
            'Megaforms Settings',
            'Megaforms',
            'manage_options',
            'PluginManager',
            [$this,'start'],
            'dashicons-format-aside',
            (float)(59.5 . srand(time())%rand(1,999))
        );

        Registry::getInstance()->settings->register_admin_settings_pages();
    }


    public function start()
    {
        if($this->_is_ajax){
            // TODO: return content
        }
        if(!$this->isPluginRoute()){
            return ;
        }

        $currentPage = $this->formPathToAdmin();

        if(is_file($currentPage) && is_readable($currentPage)){
            include_once $currentPage;
        }else{
            throw new CoreException(
                "Undefined route $currentPage",
                CoreException::UNDEFINED_ROUTE
            );
        }

        if(!class_exists($this->_controller)){
            throw new CoreException(
                "Undefined controller $this->_controller",
                CoreException::UNDEFINED_CONTROLLER
            );
        }

        if(!method_exists($this->_controller,$this->_action)){
            throw new CoreException(
                "Undefined action $this->_controller->$this->_action",
                CoreException::UNDEFINED_ACTION
            );
        }

        $currentController = new $this->_controller();
        $currentController->{$this->_action}();
    }

}
?>
