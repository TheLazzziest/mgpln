<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 13.04.16
 * Time: 4:27
 */

namespace Megaforms\Vendor\Exceptions;


class RuntimeException extends MegaformsException
{
    protected $Errors = [
        self::MISSING_FILE => 'Missing file'
    ];

    const MISSING_FILE = 120;
    public function __construct($errCode, array $param = []){
        parent::__construct($this->Errors[$errCode] . implode('',$param),$errCode);
    }
}