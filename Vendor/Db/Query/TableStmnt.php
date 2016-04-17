<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 15.02.16
 * Time: 0:42
 */

namespace Megaforms\Vendor\Db\Query;


abstract class TableStmnt extends QueryBase
{

    protected function __construct(){
        parent::__construct();
    }

    /**
     * @param $tableName
     * @param bool|false $safe
     * @return $this
     */
    protected function createTable($tableName,$safe = false){
        $this->query .= "CREATE TABLE";
        if($safe){
            $this->query .= $this->ifNotExists();
        }
        $this->query .= $this->getTableName($tableName) . ' ( ';
        return $this;
    }

    /**
     * @return $this
     */
    protected function endCreateTable(){
        $this->cleanQueryTail("\x20,.");
        $this->query .= " )";
        return $this;
    }

    /**
     * @param $tableName
     * @return $this
     */
    protected function alterTable($tableName){
        $this->query .= "ALTER TABLE " . $this->getTableName($tableName) . " ";
        return $this;
    }

    /**
     * @param $tableName
     * @param bool|false $safe
     * @return $this
     */
    protected function dropTable($tableName,$safe = false){
        $this->query .= "DROP TABLE";
        if($safe){
            $this->query .= $this->ifExists();
        }
        $this->query .= $this->getTableName($tableName) . ' ';
        return $this;
    }

    /**
     * @param $columnName
     * @param $args
     * @param $afterColumnName
     */
    protected function addColumnAfter($columnName, $args, $afterColumnName){
        $this->query .= $columnName . ' ' . $args . ' AFTER ' . $afterColumnName . ' ';
    }

    /**
     * @param $columnName
     * @param $args
     * @param bool|false $alter
     * @return $this
     */
    protected function addColumn($columnName, $args, $alter = false){
        if($alter){
            $this->query .= " ADD ";
        }
        $this->query .= $columnName . ' ' .  $args . ', ';
        return $this;
    }

    /**
     * @param $columnName
     * @param $newColumnName
     */
    protected function renameColumn($columnName, $newColumnName){
        $this->query .= "MODIFY " . $columnName . ' ' . $newColumnName . ' ';
    }

    /**
     * @param $columnName
     * @param $args
     * @return $this
     */
    protected function alterColumn($columnName, $args){
        $this->query .= "MODIFY " . $columnName . ' ' . $args;
        return $this;
    }

    /**
     * @param $columnName
     * @return $this
     */
    protected function dropColumn($columnName){
        $this->query .= "DROP " . $columnName . ' ';
        return $this;
    }

    /**
     * @param $indexName
     * @param $tableName
     * @param $columnName
     * @return $this
     */
    protected function createIndex($indexName, $tableName, $columnName){
        $this->query .= "CREATE INDEX " . $indexName . " ON " . $tableName . "(" . $columnName .") ";
        return $this;
    }

    /**
     * @param $indexName
     * @param $columnName
     * @param bool|false $alter
     * @return $this
     */
    protected function addIndex($indexName, $columnName, $alter = false){
        if($alter){
            $this->query .= "ADD ";
        }
        $this->query .= "INDEX " . $indexName . "(". $columnName ."), ";
        return $this;
    }

    /**
     * @param $indexName
     * @param $tableName
     * @return $this
     */
    protected function dropIndex($indexName, $tableName){
        $this->query .= 'DROP INDEX ' . $indexName . ' ON ' . $tableName . ' ';
        return $this;
    }

    /**
     * @param $column
     * @param $refTable
     * @param $refColumn
     * @param bool|false $alter
     * @return $this
     */
    protected function addForeignKey($column, $refTable, $refColumn,$alter = false){
        if($alter) {
            $this->query .= "ADD ";
        }
        $this->query .= 'FOREIGN KEY (' . $column .') ' . 'REFERENCES ' . $refTable . '('.$refColumn.')' . ' , ';
        return $this;
    }

    /**
     * @param $column
     * @return $this
     */
    protected function dropForeignKey($column){
        $this->query .= 'DROP FOREIGN KEY ' . $column . ' ';
        return $this;
    }

    /**
     * @param $actionType
     * @return string
     */
    protected function onUpdate($actionType){
        $this->query .= 'ON UPDATE ' . $actionType . ' ';
        return $this->query;
    }

    /**
     * @param $actionType
     * @return $this
     */
    protected function onDelete($actionType){
        $this->query .= 'ON DELETE ' . $actionType . ' ';
        return $this;
    }

    /**
     * @param bool|false $charset
     * @return $this
     */
    protected function addCharset($charset = false){
        $this->query .= "DEFAULT CHARACTER SET " .($charset !== false) ? $charset : $this->charset . ' ';
        return $this;
    }

    /**
     * @param bool|false $collate
     * @return $this
     */
    protected function addCollate($collate = false){
        $this->query .= "COLLATE " . ($collate !== false) ? $collate : $this->collate . ' ';
        return $this;
    }

    /**
     * @return $this
     */
    protected function showTables(){
        $this->query .= "SHOW TABLES ";
        return $this;
    }

    /**
     * @return string
     */
    private function ifExists(){
        return " IF EXISTS ";
    }

    /**
     * @return string
     */
    private function ifNotExists(){
        return " IF NOT EXISTS ";
    }
}
