<?php
global $wpdb;

$config = [
    '_link' => (!empty($wpdb)) ? $wpdb : '',
    '_driver' => defined("DB_DRIVER") ? DB_DRIVER : 'mysql',
    '_dbhost' => defined("DB_HOST") ? DB_HOST : '',
    '_dbname' => defined("DB_NAME") ? DB_NAME : '',
    '_dbuser' => defined("DB_USER") ? DB_USER : '',
    '_dbpassword' => defined("DB_PASSWORD") ? DB_PASSWORD : '',
    'charset' => (!empty($wpdb->charset)) ? $wpdb->charset : '',
    'collate' => (!empty($wpdb->collate)) ? $wpdb->collate : '',
    'prefix' => (!empty($wpdb->prefix)) ? $wpdb->prefix : '',
];
?>
