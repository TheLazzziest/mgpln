<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 21.02.16
 * Time: 9:48
 */

namespace Megaforms\Vendor\Exceptions;


final class DbException extends MegaformsException
{
    protected $Errors = [
        self::MISSING_SETTINGS => 'Missing db settings ',
        self::EMPTY_QUERY => 'Empty query string ',
        self::EMPTY_QUERY_STACK => 'Empty query stack ',
        self::UNDEFINED_QUERY_TYPE => 'Undefined query type ',
        self::UNDEFINED_RESULT_QUERY_TYPE => 'Undefined result query type ',
        self::NON_EXISTING_HASH => 'Non existing hash ',
        self::UNDEFINED_QUERY_HASH_INDEX => 'Undefined query hash index ',
        self::VALUE_TYPE_VIOLATION => 'Invalid value type ',
    ];

    const MISSING_SETTINGS = 503;
    const EMPTY_QUERY = 500;
    const UNDEFINED_QUERY_TYPE = 400;
    const UNDEFINED_RESULT_QUERY_TYPE = 401;
    const VALUE_TYPE_VIOLATION = 405;
    const NON_EXISTING_HASH = 502;
    const UNDEFINED_QUERY_HASH_INDEX = 430;
    const EMPTY_QUERY_STACK = 435;

    public function __constuct($errCode, array $params = []){
        parent::__construct($this->Errors[$errCode] . implode(' ',$params),$errCode);
    }

}
