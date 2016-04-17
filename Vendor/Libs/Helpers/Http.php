<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 15.04.16
 * Time: 10:00
 */

namespace Megaforms\Vendor\Libs\Helpers;


use Megaforms\Vendor\Libs\Traits\Registry;

/**
 * Class Http
 * @package megaforms\Vendor\Libs\Helpers
 */
final class Http
{
    use Registry;

    const OK = '200';

    /**
     * Make validation of the current url whether it exists
     * @param $url
     * @return bool|int
     */
    public function isValid($url){
        list($status) = get_headers($url);
        return strrpos($status[0],self::OK);
    }

    /**
     * Make a redirect
     * @param $location
     * @param int $status
     */
    public function redirect($location,$status = 302){
        wp_redirect($location,$status);
    }

    public function isAjax(){
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}