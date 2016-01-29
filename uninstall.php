<?php
if(!defined('WP_UNINSTALL_PLUGIN')){
    exit();
}
try{
        if(get_option('mgpln_activated'))
            die('Uninstalation vialation');
//            throw new WP_Error('violation',"Can't uninstal the plugin",var_dump("Pluging hasn't been installed."));
}catch(WP_Error $error){
    Mgpln_Bootstrap::admin_notice($error->get_error_message(),"error");
}
?>
