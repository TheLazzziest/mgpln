<?php
namespace Megaforms\Vendor\Libs\Api;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 26.03.16
 * Time: 9:33
 */
class ApiObserver implements \SplObserver
{
    private $apiList = [];
    /**
     *
     */
    public function __construct(array $Apis){

    }

    /**
     * @param \SplSubject $api
     */
    public function update(\SplSubject $api){

    }

    /**
     *
     */
    public function __destruct(){

    }
}
