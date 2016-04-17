<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 26.03.16
 * Time: 11:05
 */

namespace Megaforms\Vendor\Exceptions;


class LibsException extends MegaformsException
{
    private $Errors = [
        self::UNDEFINED_DOMAIN => 'Undefined domain',
        self::MISSING_PARENT_PLUGIN => 'Missing parent plugin'
    ];
    const UNDEFINED_DOMAIN = 301;
    const MISSING_PARENT_PLUGIN = 302;

    public function __construct($errCode, array $param = []){
        parent::__construct($this->Errors[$errCode]. implode(' ', $param),$errCode);
    }
}
