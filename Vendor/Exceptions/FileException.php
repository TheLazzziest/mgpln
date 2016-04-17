<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 15.04.16
 * Time: 9:17
 */

namespace Megaforms\Vendor\Exceptions;


/**
 * Class FileException
 * @package Megaforms\Vendor\Exceptions
 */
final class FileException extends MegaformsException
{
    /**
     * @property array $Errors
     */
    protected $Errors = [
        self::INACCESSIBLE_PATH => 'Inaccessible path : ',
        self::WRITTING_ERROR => 'Writting error : '
    ];
    const INACCESSIBLE_PATH = 232;
    const WRITTING_ERROR = 233;

    public function __construct($errCode,array $params = []){
        parent::__construct($this->Errors[$errCode].implode(' ', $params),$errCode);
    }
}