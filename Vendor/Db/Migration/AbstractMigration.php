<?php
namespace Megaforms\Vendor\Db\Migration;

use Megaforms\Vendor\Db\TableManager;
use Megaforms\Vendor\Libs\Helpers;

abstract class AbstractMigration
{
    protected $tManager;

    static protected $queries = [];

    public function __construct(){
        $this->tManager = new TableManager();
    }

    static private function flushQueries(){
        self::$queries = [];
    }

    protected function commit(){
        $this->tManager->commit(self::$queries);
        self::flushQueries();
    }

    abstract public function up();

    abstract public function down();





}
?>
