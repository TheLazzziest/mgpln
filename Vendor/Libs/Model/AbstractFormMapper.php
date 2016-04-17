<?php
namespace Megaforms\Vendor\Libs\Model;
use Megaforms\Vendor\Db\QueryManager;
use SplObjectStorage;
use SplObserver;


/**
 * Created by PhpStorm.
 * User: max
 * Date: 26.03.16
 * Time: 10:52
 */
abstract class FormObjectStorage extends SplObjectStorage implements SplObserver
{
    /**
     * Class QueryManager
     * @property $qManager;
     */
    protected $qManager;

    /**
     * @param QueryManager $manager
     */
    protected function __construct(QueryManager $manager){
        $this->qManager = $manager;
    }


}
