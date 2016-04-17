<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 15.02.16
 * Time: 0:09
 */

namespace Megaforms\Vendor\Db\Query;


use Megaforms\Vendor\Exceptions\ArgException;
use Megaforms\Vendor\Libs\Helpers\CommonHelpers;

/**
 * Class CRUDBase
 * @package Megaforms\Vendor\Db\Query
 */
abstract class CRUDBase extends QueryBase
{
    /**
     *
     */
    protected function __construct(){
        parent::__construct();
    }

    /**
     * @param array $params
     * @param $tableName
     * @return $this
     */
    public function select(array $params = ['*'], $tableName){
        $this->query .= "SELECT " . $this->parseColumns($params) . " FROM " . $this->getTableName($tableName) . " ";
        return $this;
    }


    /**
     * @param array $columns Parameter must be passed
     * as assoc array in form [ Column_Name => Column_Value ]
     * @param $table
     * @return $this
     */
    public function insert(array $params, $tableName ){
        $this->query .= "INSERT INTO " . $this->getTableName($tableName) . " (" . $this->parseColumns($params) .") " .
            "VALUES (" .  $this->parseValues($params) . ") ";
        return $this;
    }

    /**
     * @param array $params
     * @param $tableName
     * @return $this
     * @throws ArgException
     */
    public function replace(array $params, $tableName){
        $this->query .= "REPLACE INTO " . $this->getTableName($tableName) . " (" . $this->parseColumns($params) . ") " .
            "VALUES (" . $this->parseValues($params) . ") ";
        return $this;
    }

    /**
     * @param array $params
     * @param $tableName
     * @return $this
     */
    public function update(array $params, $tableName){
        $this->query .= "UPDATE " . $this->getTableName($tableName) . " SET " .
            array_walk($params,function(&$key, &$value){
                return "$key = $value";
            });
        return $this;
    }

    /**
     * @param $params
     * @return $this
     */
    public function delete($tableName){
        $this->query .= "DELETE FROM " . $this->getTableName($tableName) . " ";
        return $this;
    }

    /**
     * @param $tableName
     */
    public function truncate($tableName){
        $this->query .= "TRUNCATE TABLE " . $this->getTableName($tableName) . " ";
        return $this;
    }

    /**
     * Get columns name from associative array
     * @param array $columns
     * @return string
     * @throws ArgException
     */
    private function parseColumns(array $columns){
        if(CommonHelpers::isAssoc($columns)){
            return ltrim(',',implode(',', array_keys($columns)));
        }else if(is_array($columns)){
            return ltrim(',',implode(',', $columns));
        }else{
            throw new ArgException(
                ArgException::INVALID_PARAMETER_TYPE
                );
        }
    }

    /**
     * Get column values from associative array
     * @param array $columns
     * @return string
     * @throws ArgException
     */
    private function parseValues(array $columns){
        if(CommonHelpers::isAssoc($columns)){
            return ltrim(',',implode(',', array_values($columns)));
        }else{
            throw new ArgException(
                ArgException::INVALID_PARAMETER_TYPE,
                ['associative array given']
            );
        }
    }
}
