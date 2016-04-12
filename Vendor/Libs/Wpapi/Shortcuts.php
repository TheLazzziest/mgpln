<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 26.03.16
 * Time: 11:18
 */

namespace Megaforms\Vendor\Libs;


use Megaforms\Vendor\Core\Loader;
use Megaforms\Vendor\Exceptions\LibsException;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

final class Shortcuts
{
    // the collection of action links of settings for the plugin
    private $_action_links = [];


    public function __construct()
    {
//        if(!current_user_can('manage_options')){
//            throw new LibsException(
//                __("You don't have proper permissions to manage options", 'megaforms')
//            );
//        }
    }
    /**
     * @param $link
     * @return bool|int
     */
    public function add_plugin_action_link($key,$link,$name)
    {
        if($this->action_link_exists($key)){
            return false;
        }
        $this->_action_links[$key] = ['link'=> $link, 'name' => $name];
        return count($this->_action_links);
    }

    /**
     * @param $link
     * @return bool
     */
    public function remove_plugin_action_link($key)
    {
        if($this->action_link_exists($key)){
            return false;
        }
        unset($this->_action_links[$key]);
        return true;
    }

    /**
     * @param $link
     * @return bool
     */
    public function action_link_exists($link)
    {
        return in_array($link,$this->_action_links);
    }

    public function register_action_links()
    {
        foreach($this->_action_links as $key => $action_link){
            $this->_action_links[$key] = "<a href=" .
                admin_url(
                    "megaforms.php?page=".
                    $action_link['link']
                ) .
            ">"
            .$action_link['name'] .
            "</a>";

        }
        return $this->_action_links;
    }
}
