<?php

namespace Vendor\Core;

class Templater {

    private $__path;

    private $__header;

    private $__footer;

    public function __construct(){

    }

    public function render($file, array $params)
    {
        $this->render_header();
        $this->render_footer();
    }

}
?>
