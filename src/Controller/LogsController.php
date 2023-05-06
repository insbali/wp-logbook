<?php

namespace Solvrtech\WPlogbook\Controller;

use DateTime;
use Solvrtech\WPlogbook\Handler\LogHandler;
use Solvrtech\WPlogbook\Model\LogModel;

class LogsController
{
    public static function get_error_logs()
    {
        $log_model = new LogModel;
        $from_log_file = file_get_contents(WP_CONTENT_DIR . "/debug.log");

        $logs = str_replace("[Step Debug]", "", $from_log_file);
        $logs = explode("[", $logs);

        foreach ($logs as $log) {
            if (!strpos($log, "Xdebug") && !empty($log)) {

                // Split and reformat datetime
                $split_log = explode("] ", $log);
                $new_format = new DateTime($split_log[0]);
                $datetime = $new_format->format("Y-m-d H:i:s"); //Datetime

                // Split and get the level, message, file, and stacktrace
                $message_level_file = explode("@@@", str_replace("Stack trace", "@@@Stack trace", $split_log[1]));
                $message_level = explode("@@@", str_replace(":  ", "@@@", $message_level_file[0])); //Level
                $message_file = explode("@@@", str_replace("in /", "@@@", $message_level[1])); //Message and file

                // Split stacktrace if already exists
                $final_stacktrace = null;
                if (strpos($split_log[1], "Stack trace")) {

                    $split_stacktrace = explode("Stack trace:", $split_log[1]);
                    $split_stacktrace = explode("{main}", ltrim(preg_replace('/(#\d)|(")/', "", $split_stacktrace[1])));
                    $stacktrace = explode(" /", $split_stacktrace[0]);
                    $throw = explode("@@@", str_replace(" throw", "@@@throw", ltrim($split_stacktrace[1])));

                    // Final stacktrace
                    $final_stacktrace = array_merge($stacktrace, $throw);
                }

                $log_model->setMessage($message_file[0])
                    ->setFile($message_file[1])
                    ->setStackTrace($final_stacktrace)
                    ->setCode(400)
                    ->setLevel(self::replace_log_level($message_level[0]))
                    ->setChannel("default")
                    ->setDateTime($datetime);

                $handler = new LogHandler;
                $handler->send_log($log_model);
            }
        }
    }

    public static function replace_log_level($level)
    {
        switch ($level) {
            case $level === "PHP Fatal error" || $level === "PHP Parse error":
                return LogModel::CRITICAL;
                break;
            case $level === "PHP Warning":
                return LogModel::WARNING;
                break;
            case $level === "PHP Notice":
                return LogModel::NOTICE;
                break;
            case $level === "PHP Deprecated":
                return LogModel::INFO;
                break;
            default:
                # code...
                break;
        }
    }
}

LogsController::get_error_logs();
