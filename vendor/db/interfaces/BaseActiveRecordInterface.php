<?php
namespace Vendor\Db\Interfaces;

use Vendor\Db\QueryBase;

interface BaseActiveRecordInterface extends QueryBase {
    public function select();
    public function insert();
    public function update();
    public function delete();
    public function where();
    public function from();
}
?>;
