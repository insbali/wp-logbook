<?php

namespace Solvrtech\WPlogbook\Check;

if (!defined("ABSPATH")) {
    exit;
}

use Exception;
use Solvrtech\WPlogbook\Model\HealthCheckModel;
use Solvrtech\WPlogbook\Exception\HealthCheckException;

class UsedDiskCheck
{
    public static function get_key()
    {
        return 'used-disk';
    }

    public function run(): HealthCheckModel
    {
        $health_model = new HealthCheckModel;

        try {
            $used_disk = self::check();

            $health_model->set_status(HealthCheckModel::OK)
                ->set_meta([
                    "usedDiskSpace" => $used_disk,
                    "unit"  => "%"
                ]);
        } catch (Exception $exception) {
            // ...
        }

        return $health_model->set_key(self::get_key());
    }

    private static function check(): int
    {
        $cpu_check = shell_exec('df -P .');
        preg_match('/(\d*)%/', $cpu_check, $matches);

        if ($matches === null)
            throw new HealthCheckException();

        return (int) $matches[0];
    }
}
