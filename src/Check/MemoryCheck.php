<?php

namespace Solvrtech\WPlogbook\Check;

if (!defined("ABSPATH")) {
    exit;
}

use Exception;
use Solvrtech\WPlogbook\Model\HealthCheckModel;

class MemoryCheck
{
    public static function get_key()
    {
        return 'memory';
    }

    public function run(): HealthCheckModel
    {
        $health_model = new HealthCheckModel;

        try {
            $memory = self::check();
            $health_model->set_status(HealthCheckModel::OK)
                ->set_meta([
                    "memoryUsage" => $memory,
                    "unit" => "Mb"
                ]);
        } catch (Exception $exception) {
            //...
        }

        return $health_model->set_key(self::get_key());
    }

    private static function check(): float|int
    {
        return round(memory_get_usage() / 1048576, 2);
    }
}
