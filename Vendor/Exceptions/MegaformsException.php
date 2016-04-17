<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 11.02.16
 * Time: 18:57
 */

namespace Megaforms\Vendor\Exceptions;


class MegaformsException extends \Exception
{
    protected $Errors = [];

    public function __construct($message,$errCode){
        parent::__construct($message,$errCode);
    }
}
