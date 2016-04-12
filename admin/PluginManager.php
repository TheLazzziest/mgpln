<?php

use Megaforms\Vendor\Libs\Controller\BaseController;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");
/**
 * Created by PhpStorm.
 * User: max
 * Date: 30.03.16
 * Time: 0:20
 */


final class FormController extends BaseController{

    /**
     *
     */
    public function index(){
        print "Hello";
//        $this->_view->render('index');
    }


    /**
     * @param array $formData
     */
    public function addForm(array $formData){

    }

    /**
     * @param $id
     */
    public function editForm($id){

    }


    /**
     * @param $id
     */
    public function deleteForm($id){

    }
}

final class ApiController extends BaseController{

    /**
     *
     */
    public function index(){

    }

    public function setUp($api){

    }
}

