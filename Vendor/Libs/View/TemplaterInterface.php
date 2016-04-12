<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 10.04.16
 * Time: 5:58
 */

namespace megaforms\Vendor\Libs\View;


interface TemplaterInterface
{
    public function render($file, array $params = []);
}
