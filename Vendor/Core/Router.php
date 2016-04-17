<?php
namespace Megaforms\Vendor\Core;

use Megaforms\Vendor\Exceptions\ArgException;
use Megaforms\Vendor\Exceptions\CoreException;
use Megaforms\Vendor\Libs\Helpers\Http;
use Megaforms\Vendor\Libs\Helpers\PluginDataSet;
//use Megaforms\Vendor\Libs\Shortcuts;
use Megaforms\Vendor\Libs\Traits\Registry;
use Megaforms\Vendor\Libs\Wpapi\Loader;
use Megaforms\Vendor\Libs\Wpapi\Settings;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

/**
 * Final Class Router
 * @since 1.0.0
 * @method static $this instance
 * @package Megaforms\Vendor\Core
 */
final class Router {

    use Registry;

    /** @var string */
    protected $_page = '';

    /** @var string */
    protected $_controller = '';

    /** @var string */
    protected $_action = '';

    /** @var string|NULL */
    protected $_param;

    /** @var bool */


    /** @var string */
    protected $_ajax_prefix = 'wp_megaforms_ajax';


    /**
     * List of admin pages in megaforms plugin
     * @var array
     */
    protected $_admin_pages = [
        'MegaformsPage' => __DIR__ . '/MegaformsPage.php'
    ];

    /** @var  array list of Admin sub-pages */
    protected $_admin_sub_pages;

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
        $this->_param = empty($_REQUEST['params']) ? [] : $_REQUEST['params'];
    }

    /**
     * Form access path to particular page file in admin folder
     */
    private function formPath(){
        if(in_array($this->_page,array_keys($this->_admin_pages))){
            return $this->_admin_pages[$this->_page];
        }else{
            throw new ArgException(ArgException::WRONG_PARAMETER,
                [$this->_page]
            );
        }
    }

    private function isPluginRoute(){

        return !empty($this->_page) &&
                array_key_exists($this->_page,$this->_admin_pages) &&
                !empty($this->_controller) &&
                !empty($this->_action);

    }

    /**
     * Check the existence of the destination file
     * @param $routeToFile
     * @return bool|int
     */
    private function followRoute($routeToFile){
        if(is_file($routeToFile) && is_readable($routeToFile)){
            return true;
        }else{
            return CoreException::UNDEFINED_ROUTE;
        }
    }

    private function formFullPath($class){
        return __NAMESPACE__ . '\\' . $class;
    }

    private function isPathCorrect(){
        if(!class_exists($this->formFullPath($this->_controller)) ){
            return CoreException::UNDEFINED_CONTROLLER ;
        }else if(!method_exists($this->formFullPath($this->_controller), $this->_action)){
            return CoreException::UNDEFINED_ACTION ;
        }

        return true;


    }

    public function init_admin_settings(){

        Settings::load()->add_admin_settings_page(
            'Megaforms Settings',
            'Megaforms',
            'manage_options',
            'MegaformsPage',
            [$this,'start'], // in order to make routing always add to function Router method start
            'dashicons-format-aside',
            (float)(59.9 . srand(time())%rand(1,999))
        );

        Loader::load()->add_action(
            'admin_menu',
            Settings::load(),
            'register_admin_settings_pages'
        );


    }


    public function start()
    {
        PluginDataSet::load()->is_ajax = Http::load()->isAjax();

        if(is_admin() && !PluginDataSet::load()->is_ajax){
            $this->init_admin_settings();
        }

        $this->parseRequest();


        if(!$this->isPluginRoute()){
            return ;
        }

        $currentPage = $this->formPath();

        if(($error = $this->followRoute($currentPage)) === false){
            throw new CoreException($error,[$currentPage]);
        }else{
            include_once $currentPage;
        }

        if(($error = $this->isPathCorrect()) !== true){
            throw new CoreException(
                $error,
                [
                    $this->_page,
                    $this->_controller,
                    $this->_action
                ]);
        }

        $classname = $this->formFullPath($this->_controller);
        $currentController = new $classname();
        call_user_func_array([$currentController,$this->_action],$this->_param);

    }
}
?>
