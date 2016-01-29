<?php
namespace Vendor\Core;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the wp plugin");

class Router {
    /**
     * URL to http://site.domain/wp-admin/admin.php
     * @var string
     */
    protected $_admin_url;

    /** @var string */
    protected $_page = '';

    /** @var string */
    protected $_controller = '';

    /** @var string */
    protected $_action = '';

    /** @var string|NULL */
    protected $_param;

    /** @var bool */
    protected $_is_ajax = FALSE;

    /** @var string */
    protected $_ajax_prefix = '';

    protected $_menu_title = '';
    protected $_menu_slug_prefix = 'megaforms';
    protected $_main_menu_slug;
    protected $_sub_menu_items = [];
    protected $_settings_capability = 'manage_options';

    public function __construct()
    {
        $this->_admin_url = admin_url();
    }

    private function parseRequestParams()
    {

    }

    private function formRequestPath()
    {

    }

    public function start()
    {

    }

}
?>
