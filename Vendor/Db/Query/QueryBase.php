<?php
namespace Megaforms\Vendor\Db\Query;

use Megaforms\Vendor\Db\Adapter;
use Megaforms\Vendor\Exceptions\DbException;


abstract class QueryBase
{
    //Error Codes
    const UNDEFINED_QUERY_TYPE = 400;
    const UNDEFINED_RESULT_QUERY_TYPE = 401;
    const VALUE_TYPE_VIOLATION = 405;

    //

    //
    const MIN_INDEX = 0;
    private $_adapter;

    protected $charset;
    protected $collate;
    protected $prefix;

    //TODO: Fix relations between query and its result

    protected $qQuantity = 0;

    private $queries = [];
    private $queryHashList = [];
    protected $results = [];

    protected $query;

    private $OldQueries = [];
    private $OldPrepares = [];

    private $objTypes = ['ARRAY_N', 'ARRAY_A', 'OBJECT'];

    /**
     * @param bool|false $adapter
     */
    protected function __construct($adapter = false)
    {
        if ($adapter !== false) {
            $this->_adapter = $adapter;
        } else {
            $this->_adapter = Adapter::get_instance();
        }
        $this->charset = $this->_adapter->getCharset();
        $this->collate = $this->_adapter->getCollate();
        $this->prefix = $this->_adapter->getPrefix();
    }

    /**
     * @param $tableName
     * @return string
     */
    protected function getTableName($tableName){
        return $this->prefix . $tableName;
    }


    /**
     * Fire a query and save the result data
     * @param $sql
     * @param $objType
     * @return mixed
     */
    protected function resultQuery($sql,$objType){
        return $this->_adapter->get_link()->get_results($sql,$objType);
    }

    /**
     * Fire a query without saving the result data
     * @param $sql
     * @return \PDOStatement
     */
    protected function query($sql){
        return $this->_adapter->get_link()->query($sql);
    }

    /**
     * Fire the prepare statement without saving the result data
     * @param $sql
     * @param array $bindings
     * @return \PDOStatement
     */
    protected function prepare($sql,array $bindings){
        return $this->_adapter
            ->get_link()
            ->query($this->_adapter->prepare($sql,$bindings));
    }

    /**
     * Fire the prepare statement and save the result data
     * @param $sql
     * @param array $bindings
     * @param $objType
     * @return mixed
     */
    protected function resultPrepare($sql,array $bindings, $objType){
        return $this->_adapter
            ->get_link()
            ->get_results($this->_adapter->prepare($sql,$bindings), $objType);
    }


    /**
     * @param bool|false $result
     * @param bool|false $resultType
     * @return int
     */
    protected function addQuery($result = false, $resultType = false){
        if($this->isQueryEmpty()) {
            throw new DbException("Empty query string");
        }
        $this->qQuantity++;

        $theHash = $this->makeHash($this->qQuantity);
        $this->queryHashList[] = $theHash;

        $this->query = $this->endQuery($this->query);
        $this->queries[$theHash] = $this->query;

        if(is_bool($result) && $result === false){

            $this->results[$theHash] = $result;

        }else if(is_bool($result) && $result === true){

            if(in_array($resultType,$this->objTypes)){
                $this->results[$theHash] = [$result, 'resultType' => $resultType];
            }else{
                throw new DbException(
                    "Undefined result query type. Result query type must be one of types:" . array_walk($this->objTypes,'print'),
                    self::UNDEFINED_RESULT_QUERY_TYPE
                );
            }

        }else{
            throw new DbException(
                "Result value type violation. Result must be of boolean type",
                self::VALUE_TYPE_VIOLATION
            );
        }


        $this->query = '';
        // Return is optional (mainly for debugging)
        return $theHash;
    }

    /**
     * Add prepare statements to the Query Stack
     * @param $bindings
     * @param bool|false $result
     * @param bool|false $resultType
     * @return int
     */
    protected function addPrepare($bindings,$result = false, $resultType = false){
        if($this->isQueryEmpty()) {
            throw new DbException("Empty query string");
        }
        $this->qQuantity++;

        $theHash = $this->makeHash($this->qQuantity);
        $this->queryHashList[] = $theHash;

        $this->query = $this->endQuery($this->query);
        $this->queries[$theHash] = ['query' => $this->query, 'bind' => $bindings];

        if(is_bool($result) && $result === false){
            $this->results[$theHash] = $result;
        }else if(is_bool($result) && $result === true){
            if(in_array($resultType,$this->objTypes)) {
                $this->results[$theHash] = [
                    $result,
                    'resultType' => $resultType,
                    'prepare' => true
                ];
            }else{
                throw new DbException(
                    "Undefined result query type. Result query type must be one of types:" . array_walk($this->objTypes,'print'),
                    self::UNDEFINED_RESULT_QUERY_TYPE
                );
            }
        }else{

            throw new DbException(
                "Result value type violation. Result must be of boolean type",
                self::VALUE_TYPE_VIOLATION
            );
        }

        $this->query = '';
        return $theHash;
    }


    /**
     * @throws DbException
     */
    protected function doQueries(){
        foreach($this->queries as $index => $query){
            if(array_key_exists('prepare',$this->results[$index])){
                $this->execute();
            }else{
                $this->doQuery();
            }
        }
    }

    /**
     * @param bool|false $queryIndex
     * @return null
     * @throws DbException
     */
    protected function doQuery($qHashIndex = false){
        if ($this->isQueryStackEmpty()) {
            return null;
        }

        if(is_bool($qHashIndex) && $qHashIndex === false){
            $lastHashIndex = count($this->queries) - 1;
            $qHashIndex = $this->queryHashList[$lastHashIndex];
        }else if(is_string($qHashIndex) || !empty($qHashIndex)){
            $qIndex = $this->findHashIndex($qHashIndex);
            if($qIndex === false){
                throw new DbException("The hash {$qHashIndex} doesn't exist", self::NON_EXISTING_HASH);
            }
        }else{
            throw new DbException('Undefined query hash index', self::UNDEFINED_QUERY_HASH_INDEX);
        }


        if($this->withResult($this->results[$qHashIndex])){
            $this->results[$qHashIndex] = $this->resultQuery($this->queries[$qHashIndex],$this->results[$qHashIndex]['resultType']);
        }else{
            $this->results[$qHashIndex] = $this->Query($this->queries[$qHashIndex]);
        }
        $this->removeFiredQuery($qHashIndex);
        return $this->getQueryResult($qHashIndex);
    }

    /**
     * @param bool|false $prepareIndex
     * @return null
     * @throws DbException
     */
    protected function execute($pHashIndex = false){
        if($this->isQueryStackEmpty())
            return null;

        if(is_bool($pHashIndex) && $pHashIndex === false){
            $lastHashIndex = count($this->queries) - 1;
            $pHashIndex = $this->queryHashList[$lastHashIndex];
        }else if(is_string($pHashIndex) || !empty($pHashIndex)){
            $pIndex = $this->findHashIndex($pHashIndex);
            if($pIndex === false)
                throw new DbException("The hash {$pHashIndex} doesn't exist", self::NON_EXISTING_HASH);
        }else{
            throw new DbException('Undefined query hash index', self::UNDEFINED_QUERY_HASH_INDEX);
        }

        if($this->withResult($this->results[$pHashIndex])){
            $this->results[$pHashIndex] = $this->resultPrepare($this->queries[$pHashIndex]['query'],$this->queries[$pHashIndex]['bind'],$this->results[$pHashIndex]['resultType']);
        }else{
            $this->results[$pHashIndex] = $this->prepare($this->queries[$pHashIndex]['query'], $this->queries[$pHashIndex]['bind']);
        }
        $this->removeFiredQuery($pHashIndex);
        return $this->getQueryResult($pHashIndex);
    }


    /**
     * @param $queryIndex
     *
     */
    private function removeFiredQuery($hashIndex){
        if($this->isQueryStackEmpty())
            throw new DbException("Query stack is empty");

        $firedQuery = $this->queries[$hashIndex];
        $qType = $this->findFiredQueryType();
        $this->recordFiredQuery($hashIndex,$firedQuery,$qType);

        unset($this->queries[$hashIndex]);
//       @TODO: check the neccessity of removing hash from the hashList
//        unset($this->queryHashList[$this->findHashIndex($hashIndex)]);
        $this->qQuantity--;
        return $this->isQueryRemoved($hashIndex);
    }

    /**
     * @param $hashIndex
     * @return bool
     *
     */
    private function isQueryRemoved($hashIndex){
        return $this->findHashIndex($hashIndex) === false &&
                array_key_exists($hashIndex,$this->queries) === false;
    }

    /**
     * @param $firedQuery
     * @param $queryType
     */
    private function recordFiredQuery($hashKey,$firedQuery, $queryType){
        if(!strcmp($queryType,'execute')){
            $this->OldPrepares[$hashKey] = ['query' => $firedQuery['query'], 'bind' => $firedQuery['bind']];
        } else if(!strcmp($queryType,'doQuery')) {
            $this->OldQueries[$hashKey] = ['query' => $firedQuery];
        }else{
            throw new DbException("Undefined type of fired query");
        }
    }

    /**
     * @return string
     */
    private function findFiredQueryType(){
        $tracePath = debug_backtrace();
        $value = $this->findTypeByDebugKey($tracePath,['function'],['execute','doQuery']);
        if($value === null) {
            throw new DbException('Undefined query type', self::UNDEFINED_QUERY_TYPE);
        }
        return $value;
    }

    /**
     * Determine fired query type( Prepare statement or Query)
     * @param array $debuginfo
     * @param string $keyIndex
     * @param string $keyVal
     * @param bool|false $isRecursive
     *
     * !!!ATTENTION!!! Before doing recursive iteration over array, think twice.
     * Otherwise it could break this script because of runtime error
     *
     */
    private function findTypeByDebugKey(array $debuginfo,array $keyIndexes, array $keyVals, $isRecursive = false) {
        foreach($debuginfo as $traceLevel => $traceData) {
            if(is_array($traceData) || is_object($traceData)){
                if($isRecursive === true) {
                    return $this->findTypeByDebugKey($traceData,$keyIndexes,$keyVals,$isRecursive);
                } else {
                    $foundKeys = array_intersect(array_keys($traceData),$keyIndexes);
                    if(is_array($foundKeys) && !empty($foundKeys)) {
                        //Filter $traceVals out of objects and remain only arrays
                        $traceVals = array_values($traceData);
                        $traceVals = array_filter($traceVals, function($val){
                            return !is_object($val);
                        });
                        // end of filtering
                        $foundVals = array_intersect($traceVals,$keyVals);
                        if(is_array($foundVals) && !empty($foundVals)) {
                            return array_shift($foundVals);
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
     * @param $hash
     * @return mixed
     * @throws DbException
     *
     */
    protected function getQueryResult($hash){
        if (!is_string($hash) || empty($hash)) {
            throw new DbException("Undefined hash index", self::HASH_INDEX);
        }
        return $this->results[$hash];
    }

    /**
     * @param $characters
     * @return string
     */
    protected function cleanQueryTail($characters){
        if(is_string($characters)) {
            return rtrim($this->query, $characters);
        }
        return $this->query;
    }
    /**
     * @return bool
     */
    private function isQueryStackEmpty()
    {
        return empty($this->queries);
    }

    /**
     * @return bool
     */
    private function isQueryEmpty()
    {
        return empty($this->query);
    }

    /**
     * Check whether result is required after the query has been fired
     * @param $flag
     * @return bool
     */
    private function withResult($flag){
        return is_array($flag);
    }

    /**
     * Hash function for generating unique index for a particular query
     * @param $key
     * @param string $algo
     * @return string
     */
    private function makeHash($key,$algo = 'base64'){
        $data = $key . sprintf("%d",time()); // Hash of a query
        return !strcmp($algo,'base64') ? base64_encode($data) : hash($algo,$data);
    }

    /**
     * @param $query
     * @return string
     */
    private function endQuery($query){
        return $query . ';';
    }


    /**
     * Search query hash index
     * @param $hash
     * @return mixed
     */
    private function findHashIndex($hash){
        return array_search($hash,$this->queryHashList);
    }
}
?>
