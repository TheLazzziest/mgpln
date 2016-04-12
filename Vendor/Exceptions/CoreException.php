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
    const UNDEFINED_ROUTE = 404;
    //
    const UNDEFINED_CONTROLLER = 419;
    const UNDEFINED_ACTION = 420;
    //
}
