<?php

namespace Solvrtech\WPlogbook\Service;

use DateTime;
use Exception;
use Solvrtech\WPlogbook\Model\LogModel;
use Solvrtech\WPlogbook\Model\ClientModel;
use Solvrtech\WPlogbook\Handler\LogHandler;

class LogService
{
    public function get_error_logs($all_log)
    {
        try {

            $INTRV = wp_get_schedules();
            $SCEDULES = _get_cron_array();
            $NEXT_S = wp_next_scheduled('schedule_log_check_hook');

            if (!empty($all_log)) {

                $logs = str_replace("[Step Debug]", "", $all_log);

                $logs = explode("[", $logs);

                $log_model = new LogModel;
                $final_log_data = array();

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

                        $client = $_SERVER;
                        $level_code = self::replace_log_level_code($message_level[0]);
                        $final_log_data[] = array(
                            'message' => $message_file[0],
                            'file' => $message_file[1],
                            'stacktrace' => $final_stacktrace,
                            'code' => $level_code['CODE'],
                            'level' => $level_code['LEVEL'],
                            'channel' => 'default',
                            'datetime' => $datetime,
                            'client' => [
                                'url' => $client['REQUEST_URI'],
                                'server' => $client['SERVER_NAME'],
                                'httpMethod' => $client['REQUEST_METHOD'],
                                'ip' => $client['REMOTE_ADDR'],
                                'userAgent' => $client['HTTP_USER_AGENT']
                            ]
                        );
                    }
                }

                foreach ($final_log_data as $final_log) {
                    $log_model->setMessage($final_log['message'])
                        ->setFile($final_log['file'])
                        ->setStackTrace($final_log['stacktrace'])
                        ->setCode($final_log['code'])
                        ->setLevel($final_log['level'])
                        ->setChannel($final_log['channel'])
                        ->setDateTime($final_log['datetime'])
                        ->setClient((new ClientModel)->setUrl($final_log['client']['url'])
                                ->setServer($final_log['client']['server'])
                                ->setHttpMethod($final_log['client']['httpMethod'])
                                ->setIp($final_log['client']['ip'])
                                ->setUserAgent($final_log['client']['userAgent'])
                        );

                    $handler = new LogHandler;
                    $handler->send_log($log_model);
                }
            }
        } catch (Exception $e) {
        }
    }

    public static function replace_log_level_code($level)
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
                break;
        }
    }
}
