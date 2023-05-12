<?php

namespace Solvrtech\WPlogbook\Service;

if (!defined("ABSPATH")) {
    exit;
}

use DateTime;

class HealthService
{
    public function get_health_response(): array
    {
        $availabel_service = [
            "Solvrtech\WPlogbook\Check\CacheCheck",
            "Solvrtech\WPlogbook\Check\CPULoadCheck",
            "Solvrtech\WPlogbook\Check\DatabaseCheck",
            "Solvrtech\WPlogbook\Check\MemoryCheck",
            "Solvrtech\WPlogbook\Check\RedisCheck",
            "Solvrtech\WPlogbook\Check\UsedDiskCheck",
        ];

        $checks = [];
        foreach ($availabel_service as $service) {
            $result = self::run_check($service);

            if ($result !== null)
                $checks[] = $result;
        }

        return [
            "datetime" => (new DateTime())->format("Y-m-d H:i:s"),
            "instanceID" => get_option("wp_logbook_config")["instance_id"],
            "checks" => $checks,
        ];
    }

    private static function run_check($service)
    {
        $key_check = new $service;
        return $key_check->run();
    }
}
