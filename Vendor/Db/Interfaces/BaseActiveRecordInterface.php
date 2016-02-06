<?php
namespace Megaforms\Vendor\Db\Interfaces;


interface BaseActiveRecordInterface extends Megaforms\Vendor\Db\QueryBase{
    public function select();
    public function insert();
    public function update();
    public function delete();
    public function where();
    public function from();
}
?>
