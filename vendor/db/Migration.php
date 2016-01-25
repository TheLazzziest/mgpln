<?php
namespace Vendor\Db;

use Vendor\Tools\Helpers;
use ArrayObject;

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
     * @return QueryResult @TODO: determine the result of the query
     */
    public function createTable( $table_name, array $columns){
        $table_name = $this->prefix . $table_name;
        $col_iterator = new ArrayObject($columns);
        $table_columns = $this->formatColumns($col_iterator);
        $sql = "CREATE TABLE IF NOT EXISTS $table_name ( $table_columns ) DEFAULT CHARACTER SET $this->charset COLLATE $this->collate;";
        return $this->query($sql);
    }

    private function formatColumns(ArrayObject $columns){
        $table_columns = "\n";
        for($iterator = $columns->getIterator(), $i = 1; $iterator->valid(); $iterator->next()){
            $table_columns .= Helpers::rltrim($iterator->key()) . ' ' . Helpers::rltrim($iterator->current());
            $table_columns .=  ($i++ < $iterator->count()) ? ",\n" : "\n";
        }
        return $table_columns;
    }

    /**
     * Drop table of plugin
     * @since 1.0.0
     *
     * @param string $table_name
     *
     * @return QueryResult @TODO: determine the result of the query
     */
    public function dropTable( $table_name){
        $sql = "DROP TABLE IF EXISTS $this->prefix.$table_name";
        return $this->query($sql);
    }

}
?>
