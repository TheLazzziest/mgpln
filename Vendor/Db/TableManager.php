<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 15.02.16
 * Time: 14:54
 */

namespace Megaforms\Vendor\Db;

use \ArrayObject;
use Megaforms\Vendor\Db\Query\TableStmnt;
use Megaforms\Vendor\Exceptions\DbException;
use Megaforms\Vendor\Exceptions\MigrationException;
use Megaforms\Vendor\Libs\Helpers\CommonHelpers;

final class TableManager extends TableStmnt
{

    private $tables;
    /**
     * Create table of the plugin
     * @since 1.0.0
     *
     * @param string $table_name
     * @param array $columns['col_name' => 'properties']
     *
     * @return QueryResult bool
     */

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param $tableName
     * @param bool|false $safe
     */
    public function createTable( $tableName, $safe = false){
        parent::createTable($tableName,$safe);
    }

    /**
     * @return int
     * @throws DbException
     */
    public function endCreateTable(){
        return parent::endCreateTable()->addQuery();
    }

    /**
     * @param array $columns
     * @return string
     */
    public function addColumns(array $columns){
        foreach($columns as $key => $value){
            $this->addColumn(
                CommonHelpers::strim($key),
                CommonHelpers::strim($value)
            );
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function addColumn($key,$value){
        parent::addColumn($key,$value);
    }

    /**
     * @param $columnName
     * @param $refTableName
     * @param $refColumnName
     */
    public function addTableForeignKey($columnName,$refTableName,$refColumnName){
        parent::addForeignKey($columnName,$refTableName,$refColumnName,false);
    }

    /**
     * @param $columnName
     * @return int
     * @throws DbException
     */
    public function removeForeignKey($columnName){
        return parent::dropForeignKey($columnName)->addQuery();
    }

    /**
     * @param $indexName
     * @param $columnName
     */
    public function createIndex($indexName,$columnName){
        parent::addIndex($indexName,$columnName,false);
    }

    /**
     * @param $indexName
     * @param $tableName
     * @return int
     * @throws DbException
     */
    public function removeIndex($indexName,$tableName){
       return parent::dropIndex($indexName,$tableName)->addQuery();
    }

    /**
     * Drop table of the plugin
     * @since 1.0.0
     *
     * @param string $table_name
     *
     * @return QueryResult bool
     */
    public function removeTable($table_name){
        return $this->dropTable($table_name,true)->addQuery(false);
    }

    /**
     * @param $queryHashes
     * @return array|null
     * @throws DbException
     * @throws MigrationException
     */
    public function commit($queryHashes){
        if(is_array($queryHashes)){
            $queryResults = [];
            $queryHashes = CommonHelpers::trim_values($queryHashes);
            foreach($queryHashes as $index => $hash){
                if(!empty($hash) && is_string($hash)){
                    $queryResults[$hash] = $this->doQuery($hash);
                }else{
                    throw new MigrationException("Undefined query hash");
                }
            }
            return $queryResults;
        }else if(is_string($queryHashes)){
            $queryHashes = CommonHelpers::strim($queryHashes);
            return $this->doQuery($queryHashes);
        }else{
            throw new MigrationException("Undefined type of query hash"); // @TODO: refactor Exception structure for derived class from Db
        }
    }

    /**
     * @param bool|false $innerPrefix
     * @return null
     * @throws DbException
     */
    private function getTables($innerPrefix = false){
        if(!empty($this->tables))
            return null;
        $queryIndex = $this->showTables()->addQuery();
        $this->doQuery($queryIndex); // remove one element from stack of queries
        $this->tables = $this->getQueryResult($queryIndex); // get result from the last fired query
        if($innerPrefix !== false){
            $foundTables = [];
            foreach($this->tables as $table){
                if(strstr($table,$innerPrefix))
                    $foundTables[] = $table;
            }
            $this->tables = $foundTables;
        }
    }

    protected function findTable($tableName){
        $this->getTables('megaforms_'); // send the prefix of the plugin tables
        foreach($this->tables as $index => $table){
            if(!strcmp($table,$tableName))
                return $index;
        }
        return false;
    }

    private function isTableExist($tableName){
        return $this->findTable($tableName);
    }
}
