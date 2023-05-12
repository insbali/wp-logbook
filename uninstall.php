<?php

if (!defined("ABSPATH") && !defined("WP_UNINSTALL_PLUGIN")) {
    exit;
}

register_uninstall_hook(__FILE__, "wp_logbook_plugin_uninstall");
function wp_logbook_plugin_uninstall()
{
    $options = array(
        'wp_logbook_config',
        'wp_logbook_status',
        'wp_logbook_log_path',
        'wp_logbook_log_schedule',
    );

    foreach ($options as $option)
        delete_option($option);
}
