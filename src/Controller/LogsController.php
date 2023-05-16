<?php

namespace Solvrtech\WPlogbook\Controller;

use DateTime;
use Exception;
use Solvrtech\WPlogbook\Handler\LogHandler;
use Solvrtech\WPlogbook\Model\LogModel;
use Solvrtech\WPlogbook\Service\Logs\InfoService;
use Solvrtech\WPlogbook\Service\Logs\NoticeService;
use Solvrtech\WPlogbook\Service\Logs\UnknownService;
use Solvrtech\WPlogbook\Service\Logs\WarningService;
use Solvrtech\WPlogbook\Service\Logs\CriticalService;

class LogsController
{
    public function register()
    {
        add_action("schedule_log_check_hook", array($this, "schedule_log_check"));
    }

    public static function schedule_log_check()
    {
        if (get_option("wp_logbook_status") == "enable" && defined("DOING_CRON")) {

            $log_file = get_option("wp_logbook_log_path");
            $log_to_proccess = file_get_contents($log_file);

            try {

                if (!empty($log_to_proccess)) {

                    $logs = str_replace("[Step Debug]", "", $log_to_proccess);
                    $logs = str_replace("[]", "@@array@@", $logs);
                    $logs = explode("[", $logs);

                    foreach ($logs as $log) {
                        if (!strpos($log, "Xdebug") && !empty($log)) {

                            $log_to_format = explode("] ", $log);

                            $datetime = new DateTime($log_to_format[0]);
                            $log_to_format[0] = $datetime->format('Y-m-d H:i:s');
                            $handler = new LogHandler;

                            switch ($log_to_format[1]) {
                                case is_int(strpos($log_to_format[1], "PHP Fatal error")):
                                case is_int(strpos($log_to_format[1], "PHP Parse error")):
                                    $handler->send_log((new CriticalService)->formatter(
                                        $log_to_format,
                                        LogModel::CRITICAL
                                    ));
                                    break;
                                case is_int(strpos($log_to_format[1], "PHP Warning")):
                                    $handler->send_log((new WarningService)->formatter(
                                        $log_to_format,
                                        LogModel::WARNING
                                    ));
                                    break;
                                case is_int(strpos($log_to_format[1], "PHP Deprecated")):
                                    $handler->send_log((new InfoService)->formatter(
                                        $log_to_format,
                                        LogModel::INFO
                                    ));
                                    break;
                                case is_int(strpos($log_to_format[1], "PHP Notice")):
                                    $handler->send_log((new NoticeService)->formatter(
                                        $log_to_format,
                                        LogModel::NOTICE
                                    ));
                                    break;
                                default:
                                    $handler->send_log((new UnknownService)->formatter(
                                        $log_to_format,
                                        LogModel::ERROR
                                    ));
                                    break;
                            }
                        }
                    }

                    file_put_contents($log_file, "");
                }
            } catch (Exception $e) {
            }
        }
    }
}
