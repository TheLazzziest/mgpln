<?php

namespace Megaforms\Vendor\Libs\Controller;


defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the WP plugin");
abstract class BaseController implements ControllerInterface
{

    protected $_view;

    abstract public function index();
}

