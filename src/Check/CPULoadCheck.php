<?php

namespace Solvrtech\WPlogbook\Check;

if (!defined("ABSPATH")) {
    exit;
}

use Exception;
use Solvrtech\WPlogbook\Model\HealthCheckModel;
use Solvrtech\WPlogbook\Exception\HealthCheckException;

class CPULoadCheck
{
    public static function get_key()
    {
        return 'cpu-load';
    }

    public function run(): HealthCheckModel
    {
        $health_model = new HealthCheckModel;

        try {
            $cpu_data = self::check();

            $health_model->set_status(HealthCheckModel::OK)
                ->set_meta($cpu_data);
        } catch (Exception $exception) {
            // ...
        }

        return $health_model->set_key(self::get_key());
    }

    private static function check(): array
    {
        $loads = array_map(function ($load) {
            return number_format((float)$load, 2, '.', '');
        }, sys_getloadavg());

        if (!$loads)
            throw new HealthCheckException();

        return [
            "cpuLoad" => [
                "lastMinute" => $loads[0],
                "last5Minutes" => $loads[1],
                "last15Minutes" => $loads[2]
            ]
        ];
    }
}
