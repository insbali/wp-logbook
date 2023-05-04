<?php

namespace Solvrtech\WPlogbook\Check;

if (!defined("ABSPATH")) {
    exit;
}

use Exception;
use Ketut\RandomString\Random;
use Solvrtech\WPlogbook\Model\HealthCheckModel;

class CacheCheck
{
    public static function get_key()
    {
        return 'cache';
    }

    public function run(): HealthCheckModel
    {
        $health_model = new HealthCheckModel;

        try {
            $status = self::check() ?
                HealthCheckModel::OK :
                HealthCheckModel::FAILED;

            $health_model->set_status($status);
        } catch (Exception $exception) {
            //...
        }

        return $health_model->set_key(self::get_key());
    }

    private static function check(): bool
    {
        $put_new_cache = (new Random)->length(5)->lowercase()->generate();
        $cache_key = "logbook-health.check." . self::get_key();

        wp_cache_set($cache_key, $put_new_cache);
        $get_put_cache = wp_cache_get($cache_key);

        return $put_new_cache === $get_put_cache;
    }
}
