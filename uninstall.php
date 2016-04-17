<?php

use Megaforms\Vendor\Core\PluginMigration;

if(!defined('WP_UNINSTALL_PLUGIN')){
    exit();
}

require_once('megaforms.php');
spl_autoload_register('MegaformsBootstrap::autoload');

try{
	/** @method PluginMigration down */
    PluginMigration::load()->down();

}catch(\Megaforms\Vendor\Exceptions\MegaformsException $error){
    Megaforms\Vendor\Libs\Helpers\CommonHelpers::handle_exception($error);
}
?>
