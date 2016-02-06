<?php
namespace Megaforms\Vendor\Core;

use \ArrayAccess;

final class Registry implements ArrayAccess{

    protected $_container;

    public function offsetSet($key, $value){
        if(!$this->offsetExists($key))
            $this->_container[$key] = $value;
    }

    public function offsetExists($key){
        return array_key_exists($key,$this->_container);
    }

    public function offsetGet($key){
        if($this->offsetExists($key))
            return $this->_container[$key];
        else
            throw new \Exception("Property doesn't exist");
    }

    public function offsetUnset($key){
        if($this->offsetExists($key))
            unset($this->_container[$key]);
        else
            throw new \Exception("You can not unset undefined property");
    }

}

?>
