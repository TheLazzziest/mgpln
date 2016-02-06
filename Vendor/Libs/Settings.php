<?php
namespace Megaforms\Vendor\Libs;

use Megaforms\Vendor\Core\Loader\AbstractLoader;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

class Settings extends AbstractLoader
{
    // https://codex.wordpress.org/Function_Reference/add_menu_page
    // the position of the menu page in admin menu structure
    private $_position = 26;

    // the collection of action links of settings for the plugin
    private $_action_links = [];

    // the collection of settings pages hooks for the plugin
    private $_settings = [];

    /**
     *
     */
    public function __construct()
    {
        if(!current_user_can('manage_options'))
            throw new \Exception("You don't have proper permissions to manage options");
        if(function_exists('add_menu_page') === false)
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    /**
     * @param $page_title
     * @param $menu_title
     * @param $capabilities
     * @param $menu_slug
     * @param string $function
     * @param string $icon_url
     * @param int $position
     * @return bool
     */
    public function add_menu_page_settings($page_title,$menu_title,$capabilities,$menu_slug, $function = '', $icon_url = '',$position = 0)
    {
        if($this->menu_slug_exists($menu_slug))
            return false;
        $this->_settings[$menu_slug]['page_title'] = $page_title;
        $this->_settings[$menu_slug]['menu_title'] = $menu_title;
        $this->_settings[$menu_slug]['capabilities'] = $capabilities;
        $this->_settings[$menu_slug]['menu_slug'] = $menu_slug;
        $this->_settings[$menu_slug]['function'] = $function;
        $this->_settings[$menu_slug]['icon_url'] = $icon_url;
        $this->_settings[$menu_slug]['position'] = $position > 0 ? $position : $this->_position;
        return count($this->_settings[$menu_slug]);
    }

    /**
     * @param $parent_slug
     * @param $page_title
     * @param $menu_title
     * @param $capabilities
     * @param $menu_slug
     * @param string $function
     * @return bool
     */
    public function add_submenu_page_settings($parent_slug, $page_title, $menu_title, $capabilities, $menu_slug, $function = '')
    {
        if($this->menu_slug_exists($parent_slug) === false)
            return false;

        $this->_settings[$parent_slug]['parent_slug'] = $parent_slug;
        $this->_settings[$parent_slug]['page_title'] = $page_title;
        $this->_settings[$parent_slug]['menu_title'] = $menu_title;
        $this->_settings[$parent_slug]['capabilities'] = $capabilities;
        $this->_settings[$parent_slug]['menu_slug'] = $menu_slug;
        $this->_settings[$parent_slug]['function'] = $function;

        return count($this->_settings[$parent_slug]);
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
            if(count($this->_settings[$menu_slug]) > 1 )
                return false;
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
     * @param $link
     * @return bool|int
     */
    public function add_plugin_action_link($link)
    {
       if($this->action_link_exists($link))
           return false;
        $this->_action_links[] = $link;
        return count($this->_action_links);
    }

    /**
     * @param $link
     * @return bool
     */
    public function remove_plugin_action_link($link)
    {
        if($this->action_link_exists($link))
            return false;
        $key = array_search($link,$this->_action_links);
        unset($this->_action_links[$key]);
        return true;
    }

    /**
     * @param $menu_slug
     * @param string $submenu_slug
     * @return bool
     */
    public function menu_slug_exists($menu_slug,$submenu_slug = '')
    {
        if(array_key_exists($menu_slug,$this->_settings)){
            if(strlen(Helpers::rltrim($submenu_slug)) > 0){
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

    public function action_link_exists($link)
    {
        return in_array($link,$this->_action_links);
    }

    public function register_admin_settings_pages()
    {
        foreach($this->_settings as $menu_slug => $pageParams)
            if(!strcmp($menu_slug,$pageParams['menu_slug']))
                add_menu_page(
                    $pageParams['page_title'], $pageParams['menu_title'], $pageParams['capability'], $pageParams['menu_slug'],$pageParams['function']
                );
            else
                add_submenu_page(
                    $pageParams['parent_slug'],$pageParams['page_title'], $pageParams['sub_menu_title'], $pageParams['capability'], $pageParams['menu_slug'], $pageParams['function']
                );
    }

    public function register_action_links()
    {
//        foreach($this->_action_links as $index => $link)

    }

    public function init_admin_menu()
    {
        $this->add_action('admin_menu',$this,'register_admin_settings_pages');
    }



}
?>
