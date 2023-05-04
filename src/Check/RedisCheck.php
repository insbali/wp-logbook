<?php

namespace Solvrtech\WPlogbook\Check;

if (!defined("ABSPATH")) {
    exit;
}

use Exception;
use Solvrtech\WPlogbook\Model\HealthCheckModel;

class RedisCheck
{
    public static function get_key()
    {
        return 'redis';
    }

    public function run(): HealthCheckModel
    {
        $health_model = new HealthCheckModel;

        try {
        } catch (Exception $exception) {
            //...
        }
        return $health_model->set_key(self::get_key());
    }

    private static function check()
    {
    }
}
