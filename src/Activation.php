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
            "api_key"   => get_option("wp_logbook_config")["api_key"] !== null ? get_option("wp_logbook_config")["api_key"] : "",
            "api_url"   => get_option("wp_logbook_config")["api_url"] !== null ? get_option("wp_logbook_config")["api_url"] : "",
            "log_level" => get_option("wp_logbook_config")["log_level"] !== null ? get_option("wp_logbook_config")["log_level"] : 0,
            "instance_id"  => get_option("wp_logbook_config")["instance_id"] !== null ? get_option("wp_logbook_config")["instance_id"] : "default",
        );

        update_option("wp_logbook_status", "disable");
        update_option("wp_logbook_log_path", get_option("wp_logbook_log_path") !== null ? get_option("wp_logbook_log_path") : "");
        update_option("wp_logbook_log_schedule", get_option("wp_logbook_log_schedule") !== null ? get_option("wp_logbook_log_schedule") : "30");
        update_option("wp_logbook_config", $options);

        flush_rewrite_rules();
    }
}
