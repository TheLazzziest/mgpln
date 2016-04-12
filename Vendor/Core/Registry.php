<?php
namespace Megaforms\Vendor\Core;

use Megaforms\Vendor\Exceptions\CoreException;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

/**
 * Class Core\Registry
 */
trait Registry {

    private static $instance;
    //** @propety array $container object storage container */
    private $_container = [];


    public static function getInstance(){
        if(empty(self::$instance)
            || ! (self::$instance instanceof Registry)){
            self::$instance = new Registry([],\ArrayObject::ARRAY_AS_PROPS);
        }
        return self::$instance;
    }



}

?>
