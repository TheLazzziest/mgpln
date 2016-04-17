<?php

namespace Megaforms\Vendor\Libs\Wpapi;


use Megaforms\Vendor\Libs\Traits\Registry;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

/**
 * Class Loader
 * @package Megaforms\Vendor\Libs\Wpapi
 */
final class Loader
{
    use Registry;
    /*
    * @access protected
    * @var array $actions The actions registered within WP Plugin
    */
    private $actions = [];

    /*
    * @access protected
    * @var array $fired_actions The fired actions registered within WP Plugin
    */
    private $fired_actions = [];

    /*
    * @access protected
    * @var array $filters The filters registered within WP
    */
    private $filters = [];

    /*
    * @access protected
    * @var array $fired_filters The fired filters registered within WP
    */
    private $fired_filters = [];
    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     *
     * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
     * @param    string               $hook             The name of the WordPress filter that is being registered.
     * @param    object               $component        A reference to the instance of the object on which the filter is defined.
     * @param    string               $callback         The name of the function definition on the $component.
     * @param    int                  $priority         The priority at which the function should be fired.
     * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
     * @return   array                                  The collection of actions and filters registered with WordPress.
     */

    protected function add($hooks,$hook,$component, $callback, $priority, $accepted_args){
        $hooks[] = [
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
        return $hooks;
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param    string               $hook             The name of the WordPress action that is being registered.
     * @param    object               $component        A reference to the instance of the object on which the action is defined.
     * @param    string               $callback         The name of the function definition on the $component.
     * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
     * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_action($hook, $component, $callback, $priority = 10,$accepted_args = 1){
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority,$accepted_args);
        return $this;
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param    string               $hook             The name of the WordPress filter that is being registered.
     * @param    object               $component        A reference to the instance of the object on which the filter is defined.
     * @param    string               $callback         The name of the function definition on the $component.
     * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
     * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
     */
    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
        return $this;
    }


    /**
     * A utility function that is used to register the plugin filters hooks
     * within Wordpress.
     */
    public function run_filters(){
        foreach($this->filters as $filter){
            add_filter(
                $filter['hook'],
                [
                    $filter['component'],
                    $filter['callback']
                ]
//                $filter['priority'],
//                $filter['accepted_args']
            );
            $this->fired_filters[] = $filter;
        }
        $this->filters = [];
    }
    /**
     * A utility function that is used to register the plugin actions hooks.
     */
    public function run_actions(){
        foreach($this->actions as $action){
            add_action(
                $action['hook'],
                [
                    $action['component'],
                    $action['callback']
                ]
//                $action['priority'],
//                $action['accepted_args']
            );
            $this->fired_actions[] = $action;
        }
        $this->actions = [];
    }

    public function run(){
        $this->run_actions();
        $this->run_filters();
    }
}
?>
