<?php
global $wpdb;

$config = [
    'link' => (!empty($wpdb)) ? $wpdb : '',
    'dbhost' => defined("DB_HOST") ? DB_HOST : '',
    'dbname' => defined("DB_NAME") ? DB_NAME : '',
    'dbuser' => defined("DB_USER") ? DB_USER : '',
    'dbpassword' => defined("DB_PASSWORD") ? DB_PASSWORD : '',
    'charset' => (!empty($wpdb->charset)) ? $wpdb->charset : '',
    'collate' => (!empty($wpdb->collate)) ? $wpdb->collate : '',
    'prefix' => (!empty($wpdb->prefix)) ? $wpdb->prefix : '',
];
?>
