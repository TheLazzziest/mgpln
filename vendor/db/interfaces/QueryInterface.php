<?php
namespace Vendor\Db\Interfaces;

interface QueryInterface {
    public function query($sql);
    public function prepare($sql, array $params);
}
?>
