<?php
namespace Megaforms\Vendor\Core;

use Megaforms\Vendor\Libs\Controller\AbstractController;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");
/**
 * Created by PhpStorm.
 * User: max
 * Date: 30.03.16
 * Time: 0:20
 */


final class FormController extends AbstractController{

    /**
     *
     */
    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $this->render('index.php',['word' => 'Hello']);
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

/**
 * Class ApiController
 * @package Megaforms\Vendor\Core
 */
final class ApiController extends AbstractController{

    /**
     *
     */
    public function index(){

    }

    /**
     * @param $api
     */
    public function setUp($api){

    }
}

