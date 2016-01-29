<?php

namespace Vendor\Core\Loader;

use Vendor\Core\Loader\LoaderInterface as LoadInterface;

abstract class AbstractLoader implements LoadInterface
{
//    /*
//    * @access protected
//    * @var array $action The actions registered with WP
//    */
//    protected $_actions = [];
//
//    /*
//    * @access protected
//    * @var array $filters The filters registered with WP
//    */
//    protected $_filters = [];
//
//    /*
//    *
//    */
//    public function __construct(){}
//
//    /**
//     * A utility function that is used to register the actions and hooks into a single
//     * collection.
//     *
//     * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
//     * @param    string               $hook             The name of the WordPress filter that is being registered.
//     * @param    object               $component        A reference to the instance of the object on which the filter is defined.
//     * @param    string               $callback         The name of the function definition on the $component.
//     * @param    int                  $priority         The priority at which the function should be fired.
//     * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
//     * @return   array                                  The collection of actions and filters registered with WordPress.
//     */
//
//    protected function add($hooks,$hook,$component, $callback, $priority, $accepted_args){
//        $hooks[] = [
//            'hook' => $hook,
//            'component' => $component,
//            'callback' => $callback,
//            'priority' => $priority,
//            'accepted_args' => $accepted_args,
//        ];
//        return $hooks;
//    }
//
//    /**
//     * Add a new action to the collection to be registered with WordPress.
//     *
//     * @param    string               $hook             The name of the WordPress action that is being registered.
//     * @param    object               $component        A reference to the instance of the object on which the action is defined.
//     * @param    string               $callback         The name of the function definition on the $component.
//     * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
//     * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
//     */
//    public function add_action($hook, $component, $callback, $priority = 10,$accepted_args = 1){
//        $this->_actions = $this->add($this->_actions, $hook, $component, $callback, $priority,$accepted_args);
//    }
//
//    /**
//     * Add a new filter to the collection to be registered with WordPress.
//     *
//     * @param    string               $hook             The name of the WordPress filter that is being registered.
//     * @param    object               $component        A reference to the instance of the object on which the filter is defined.
//     * @param    string               $callback         The name of the function definition on the $component.
//     * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
//     * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
//     */
//    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
//        $this->_filters = $this->add( $this->_filters, $hook, $component, $callback, $priority, $accepted_args );
//    }
//
//
//    /**
//     * A utility function that is used to register the plugin filters hooks
//     * within Wordpress.
//     */
//    public function run_filters(){
//        foreach($this->_filters as $filter){
//            add_filter($filter['hook'],[$filter['component'], $filter['callback']], $filter['priority'], $filter['accepted_args']);
//        }
//    }
//    /**
//     * A utility function that is used to register the plugin actions within Wordpress.
//     */
//    public function run_actions(){
//        foreach($this->_actions as $action){
//            add_action($action['action'], [$action['component'], $action['callback']], $action['priority'], $action['accepted_args']);
//        }
//    }
}
?>
