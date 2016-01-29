<?php

namespace Vendor\Core\Loader;

interface LoaderInterface {

    public function add_action($hook, $component, $callback, $priority = 10,$accepted_args = 1);

    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1);

}
?>
