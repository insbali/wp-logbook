<?php

if (!defined("ABSPATH") && !defined("WP_UNINSTALL_PLUGIN")) {
    exit;
}

register_uninstall_hook(__FILE__, "wp_logbook_plugin_uninstall");
function wp_logbook_plugin_uninstall()
{
    $options = array(
        'wp_logbook_api_key',
        'wp_logbook_api_url',
        'wp_logbook_log_level',
    );

    foreach ($options as $option)
        delete_option($option);
}
