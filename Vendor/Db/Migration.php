<?php
namespace Megaforms\Vendor\Db;

use Megaforms\Vendor\Tools\Helpers;
use \ArrayObject;

final class Migration extends QueryBase
{
    public function __construct(){
        parent::__construct();
    }

    /**
     * Create table of the plugin
     * @since 1.0.0
     *
     * @param string $table_name
     * @param array $columns['col_name' => 'properties']
     *
     * @return QueryResult bool
     */
    public function createTable( $table_name, array $columns){
        $table_name = $this->_prefix . $table_name;
        $col_iterator = new ArrayObject($columns);
        $table_columns = $this->formatColumns($col_iterator);
        $sql = "CREATE TABLE IF NOT EXISTS $table_name ( {$table_columns} ) DEFAULT CHARACTER SET {$this->_charset} COLLATE {$this->_collate};";
        return $this->query($sql);
    }

    /**
     * @param ArrayObject $columns
     * @return string
     */
    private function formatColumns(ArrayObject $columns){
        $table_columns = "\n";
        for($iterator = $columns->getIterator(), $i = 1; $iterator->valid(); $iterator->next()){
            $table_columns .= Helpers::rltrim($iterator->key()) . ' ' . Helpers::rltrim($iterator->current());
            $table_columns .=  ($i++ < $iterator->count()) ? ",\n" : "\n";
        }
        return $table_columns;
    }

    /**
     * Drop table of the plugin
     * @since 1.0.0
     *
     * @param string $table_name
     *
     * @return QueryResult bool
     */
    public function dropTable($table_name){
        $sql = "DROP TABLE IF EXISTS " . $this->_prefix . $table_name;
        return $this->query($sql);
    }

}
?>
