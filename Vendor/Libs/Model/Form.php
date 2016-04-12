<?php
namespace Megaforms\Vendor\Libs\Model;
/**
 * Created by PhpStorm.
 * User: max
 * Date: 26.03.16
 * Time: 10:52
 */
abstract class Form implements \SplSubject
{
    private $_name;
    private $_options;
    private $_observer;

    public function __construct(FormObjectStorage $storage){

    }

    public function __clone(){
        return null;
    }

    public function attach(\SplObserver $observer){

    }

    public function detach(\SplObserver $observer){

    }

    public function notify(){

    }
}
