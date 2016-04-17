<?php
namespace Megaforms\Vendor\Libs\Wpapi;

use Megaforms\Vendor\Libs\Helpers\CommonHelpers;
use Megaforms\Vendor\Libs\Helpers\PluginDataSet;
use Megaforms\Vendor\Libs\Traits\Registry;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

final class Settings
{
    /** @return Settings */
    use Registry;
    // https://codex.wordpress.org/Function_Reference/add_menu_page
    // the position of the menu page in admin menu structure
    private $_position = 26;

    // the collection of current settings pages of the Plugin
    // which is ready to be hooked
    private $_settings = [];

    // fired list of settings page of the Plugin
    private $_used_settings = [];
    /**
     *
     */

    /**
     * @param $page_title
     * @param $menu_title
     * @param $capabilities
     * @param $menu_slug
     * @param string $function
     * @param string $icon_url
     * @param int $position
     * @return bool | object
     */
    public function add_admin_settings_page($page_title,$menu_title,
                                           $capabilities,$menu_slug,
                                           $function = '', $icon_url = '',
                                           $position = 0)
    {
        if($this->menu_slug_exists($menu_slug)) {
            return false;
        }
        $this->_settings[$menu_slug]['page_title'] = $page_title;
        $this->_settings[$menu_slug]['menu_title'] = $menu_title;
        $this->_settings[$menu_slug]['capabilities'] = $capabilities;
        $this->_settings[$menu_slug]['menu_slug'] = $menu_slug;
        $this->_settings[$menu_slug]['function'] = $function;
        $this->_settings[$menu_slug]['icon_url'] = $icon_url;
        $this->_settings[$menu_slug]['position'] = $position > $this->_position ? $position : $this->_position;
        return $this;
    }

    /**
     * @param $parent_slug
     * @param $page_title
     * @param $menu_title
     * @param $capabilities
     * @param $menu_slug
     * @param string $function
     * @return bool | object
     */
    public function add_admin_settings_subpage($parent_slug, $page_title,
                                              $menu_title, $capabilities,
                                              $menu_slug, $function = '')
    {
        if($this->menu_slug_exists($parent_slug) === false) {
            return false;
        }

        $this->_settings[$parent_slug]['parent_slug'] = $parent_slug;
        $this->_settings[$parent_slug]['page_title'] = $page_title;
        $this->_settings[$parent_slug]['menu_title'] = $menu_title;
        $this->_settings[$parent_slug]['capabilities'] = $capabilities;
        $this->_settings[$parent_slug]['menu_slug'] = $menu_slug;
        $this->_settings[$parent_slug]['function'] = $function;

        return $this;
    }

    /**
     * @param $menu_slug
     * @return bool
     */
    public function remove_menu_page_settings($menu_slug)
    {
        /*
         * if a pair of keys _settings[$menu_slug][$menu_slug] corresponds each other,
         *  it means that this is a menu page, otherwise it's a submenu page
         */
        if($this->menu_slug_exists($menu_slug,$menu_slug)){
            if(count($this->_settings[$menu_slug]) > 1 ){
                return false;
            }
            unset($this->_settings[$menu_slug]);
            return true;
        }

        return false;
    }

    /**
     * @param $menu_slug
     * @param $parent_menu_slug
     * @return bool
     */
    public function remove_submenu_page_settings($menu_slug, $parent_menu_slug)
    {
        if($this->menu_slug_exists($parent_menu_slug,$menu_slug)){
            foreach($this->_settings[$parent_menu_slug] as $submenu_page){
                if(array_key_exists($menu_slug,$submenu_page)){
                    unset($submenu_page);
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * @param $menu_slug
     * @param string $submenu_slug
     * @return bool
     */
    public function menu_slug_exists($menu_slug,$submenu_slug = '')
    {
        if(array_key_exists($menu_slug,$this->_settings)){
            if(strlen(CommonHelpers::strim($submenu_slug)) > 0){
                foreach($this->_settings[$menu_slug] as $submenu_page){
                    if(array_key_exists($submenu_slug,$submenu_page))
                        return true;
                }
                return false;
            }
            return true;
        }
        return false;
    }



    /**
     *
     */
    public function register_admin_settings_pages()
    {
        foreach($this->_settings as $menu_slug => $pageParams) {

            if(!strcmp($menu_slug,$pageParams['menu_slug'])){
                add_menu_page(
                    __($pageParams['page_title'],PluginDataSet::load()->plugin_name),
                    __($pageParams['menu_title'],PluginDataSet::load()->plugin_name),
                    $pageParams['capabilities'], $pageParams['menu_slug'],
                    $pageParams['function'],$pageParams['icon_url'],
                    $pageParams['position']
                );
                $this->_used_settings[$menu_slug] = $pageParams;
            }else{
                add_submenu_page(
                    $pageParams['parent_slug'],
                    __($pageParams['page_title'],PluginDataSet::load()->plugin_name),
                    __($pageParams['sub_menu_title'],PluginDataSet::load()->plugin_name),
                    $pageParams['capabilities'],$pageParams['menu_slug'],
                    $pageParams['function']
                );
                $this->_used_settings[$menu_slug]['subpages'] = $pageParams;
            }
        }

        $this->_settings = [];

    }



}
?>
