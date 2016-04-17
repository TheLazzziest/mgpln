<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 15.04.16
 * Time: 9:38
 */

namespace Megaforms\Vendor\Exceptions;


/**
 * Class HTTPException
 * @package megaforms\Vendor\Exceptions
 */
final class HTTPException extends MegaformsException
{
    protected $Errors = [
        self::NOT_FOUND => 'Not found :'
    ];

    /**
     *
     */
    const NOT_FOUND = 404;

    public function __construct($errCode, array $params = []){
       parent::__construct($this->Errors[$errCode] . implode(' ', $params), $errCode);
    }
}