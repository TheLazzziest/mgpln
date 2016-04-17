<?php
namespace Megaforms\Vendor\Libs\Traits;


defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

/**
 * Class Registry
 * @since 1.0.0
 * @package Megaforms\Vendor\Libs\Traits
 */
trait Registry {

    protected static $_instance = [];

    protected function __construct() {}
    protected function __clone() {}
    protected function __sleep() {}
    protected function __wakeup() {}

	/**
     * @return object
     */
    public static function load(){

        $class = get_called_class();
        if(!isset(self::$_instance[$class])){

            self::$_instance[$class] = new $class();
        }

        return self::$_instance[$class];
    }



}

?>
