<?php
namespace Vendor\Db;

abstract class QueryBase extends Connector implements Interfaces\QueryInterface
{

    public function query($sql){
        return $this->get_link()->query($sql);
    }

    public function prepare($sql,array $params){
        return $this->get_link()->query($this->get_link()->prepare($sql,$params));
    }
}
?>
