<?php

namespace Solvrtech\WPlogbook\Controller;

use Solvrtech\WPlogbook\Service\LogService;

class LogsController
{
    public function register()
    {
        add_action("schedule_log_check_hook", array($this, "schedule_log_check"));
    }

    public function schedule_log_check()
    {
        if (get_option("wp_logbook_status") == "enable") {

            $log_file = get_option("wp_logbook_log_path");
            $from_log_file = file_get_contents($log_file);

            (new LogService)->get_error_logs($from_log_file);

            // Empty log file after sent all logs
            // file_put_contents($log_file, "");
        }
    }
}
