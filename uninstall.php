<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
    exit();
}
try{

    $migrator = new Megaforms\Vendor\Db\PluginMigration();

    $migrator->down();

}catch(\Megaforms\Vendor\Exceptions\MegaformsException $error){
    Megaforms\Vendor\Libs\Helpers\CommonHelpers::handle_exception($error->getMessage(), $error->getCode());
}
?>
