<?php

namespace Megaforms\Vendor\Libs\Controller;


use Megaforms\Vendor\Core\Templater;
use Megaforms\Vendor\Libs\Helpers\Http;
use Megaforms\Vendor\Libs\Helpers\PluginDataSet;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the WP plugin");

/**
 * Class AbstractController
 * @package Megaforms\Vendor\Libs\Controller
 */
abstract class AbstractController implements ControllerInterface
{

    /**
     * class Megaforms\Vendor\Core\Templater
     * @property Templater $_view
     */
    public $_view;


    protected function __construct(){
        $this->_view = Templater::load();
        $this->_view->init( PluginDataSet::load()->views_path);
    }

    protected function redirect($location,$status){
        Http::load()->redirect($location,$status);
    }


    protected function render($file, array $params = []){
        $this->_view->registerDependencies();
        $this->_view->setFile($file);
        $this->_view->setParams($params);
        echo $this->_view->render();
    }
}

