<?php

namespace Solvrtech\WPlogbook\Handler;

use Exception;
use Solvrtech\WPlogbook\Model\LogModel;

class LogHandler
{
    public function send_log(LogModel $log)
    {
        try {

            if (!function_exists('get_plugin_data')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            if ($this->getMinLevel() <= $this->intLevel($log->level)) {
                wp_remote_post(
                    get_option("wp_logbook_api_url") . '/api/log/save',
                    array(
                        "method" => "POST",
                        "headers" => array(
                            "Content-Type"  => "application/json",
                            "Accept"        => "aplication/json",
                            "x-lb-token"    => get_option("wp_logbook_api_key"),
                            "x-lb-version"  => get_plugin_data(WP_LOGBOOK_ROOT)['Version']
                        ),
                        "body" => json_encode($log)
                    )
                );
            }
        } catch (Exception $e) {
        }
    }

    private function getMinLevel()
    {
        return get_option("wp_logbook_log_level");
    }

    private function intLevel($level)
    {
        $intLevel = 0;

        try {
            $intLevel = match (strtolower($level)) {
                'debug'     => 0,
                'info'      => 1,
                'notice'    => 2,
                'warning'   => 3,
                'error'     => 4,
                'critical'  => 5,
                'alert'     => 6,
                'emergency' => 7
            };
        } catch (Exception $e) {
        }

        return $intLevel;
    }
}
