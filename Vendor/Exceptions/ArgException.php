<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 13.04.16
 * Time: 5:38
 */

namespace Megaforms\Vendor\Exceptions;


class ArgException extends MegaformsException
{
    protected $Errors = [
        self::WRONG_PARAMETER => 'Wrong parameter',
        self::INVALID_PARAMETER_TYPE => 'Invalid param type',
    ];

    const WRONG_PARAMETER = 220;
    const INVALID_PARAMETER_TYPE = 221;
    public function __construct($errCode, array $param = []){
        parent::__construct($this->Errors[$errCode] . implode(' '.$param), $errCode);
    }
}