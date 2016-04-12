<?php

namespace Megaforms\Vendor\Core;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");
final class Loader
{

    /*
    * @access protected
    * @var array $action The actions registered with WP
    */
    private $actions = [];

    /*
    * @access protected
    * @var array $filters The filters registered with WP
    */
    private $filters = [];

    /*
    *
    */
    public function __construct(){}
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
        }
    }
    /**
     * A utility function that is used to register the plugin actions within Wordpress.
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
        }
    }

    public function run(){
        $this->run_actions();
        $this->run_filters();
    }
}
?>