<?php

namespace Solvrtech\WPlogbook\Admin;

if (!defined("ABSPATH")) {
    exit;
}

use DateTime;
use WPConfigTransformer;

class ConfigSetting
{
    /**
     * Enable WP Debug, WP Debug Log
     *
     * @return void
     */
    public function config_enable_debug(): void
    {
        // Get config file
        $config_path = ABSPATH . "wp-config.php";

        $wpconfig = new WPConfigTransformer($config_path);

        // Enabled WP_DEBUG
        $wpconfig->update("constant", "WP_DEBUG", "true", array("raw" => true));

        $debug_file_loc = trailingslashit(wp_upload_dir()["basedir"] . "/wp-logbook");

        if (!file_exists($debug_file_loc)) {
            wp_mkdir_p($debug_file_loc);
        }

        if (get_option("wp_logbook_log_path") == null)
            update_option("wp_logbook_log_path", $debug_file_loc .
                (new DateTime())->format("Ymd") . "-debug.log");

        // Enable WP_DEBUG_LOG and set debug.log file location
        if ($wpconfig->exists("constant", "WP_DEBUG")) {

            if ($wpconfig->exists("constant", "WP_DEBUG_LOG")) {

                $wpconfig->update(
                    "constant",
                    "WP_DEBUG_LOG",
                    get_option("wp_logbook_log_path")
                );
            } else {

                $wpconfig->add(
                    "constant",
                    "WP_DEBUG_LOG",
                    get_option("wp_logbook_log_path")
                );
            }

            if ($wpconfig->exists("constant", "WP_DEBUG_DISPLAY")) {

                $wpconfig->update(
                    "constant",
                    "WP_DEBUG_DISPLAY",
                    "false",
                    array("raw" => true)
                );
            } else {

                $wpconfig->add(
                    "constant",
                    "WP_DEBUG_DISPLAY",
                    "false",
                    array("raw" => true)
                );
            }
        }
    }

    /**
     * Disable WP Debug, Remove WP Debug Log
     *
     * @return void
     */
    public function config_disable_debug(): void
    {
        // Get config file
        $config_path = ABSPATH . "wp-config.php";

        $wpconfig = new WPConfigTransformer($config_path);

        if ($wpconfig->exists("constant", "WP_DEBUG"))
            $wpconfig->update("constant", "WP_DEBUG", "false", array("raw" => true));

        if ($wpconfig->exists("constant", "WP_DEBUG_LOG"))
            $wpconfig->remove("constant", "WP_DEBUG_LOG");
    }
}
