<?php

namespace Solvrtech\WPlogbook;

if (!defined("ABSPATH")) {
    exit;
}

class Activation
{

    public static function plugin_activate()
    {
        $options = array(
            'wp_logbook_api_key',
            'wp_logbook_api_url',
            'wp_logbook_log_level',
        );

        foreach ($options as $option)
            add_option($option, "");

        flush_rewrite_rules();
    }
}
