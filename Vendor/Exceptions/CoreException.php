<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 26.03.16
 * Time: 11:28
 */

namespace Megaforms\Vendor\Exceptions;


final class CoreException extends MegaformsException
{
    // String represantation of Errors
    protected $Errors = [
        self::UNDEFINED_ROUTE => 'Undefined route',
        self::UNDEFINED_CONTROLLER => 'Undefined controller',
        self::UNDEFINED_ACTION => 'Undefined action'
    ];
    // General route error
    const UNDEFINED_ROUTE = 404;
    // Particular path error
    const UNDEFINED_CONTROLLER = 419;
    const UNDEFINED_ACTION = 420;
    //
    public function __construct($errCode){
        parent::__construct($this->Errors[$errCode],$errCode);
    }

}
