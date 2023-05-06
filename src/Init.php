<?php

namespace Solvrtech\WPlogbook;

if (!defined("ABSPATH")) {
    exit;
}

use Solvrtech\WPlogbook\Admin\AdminSetting;
use Solvrtech\WPlogbook\Controller\HealthController;
use Solvrtech\WPlogbook\Controller\LogsController;

final class Init
{

    private static function get_servives()
    {
        return [
            AdminSetting::class,
            HealthController::class,
            LogsController::class,
        ];
    }

    public static function register_services()
    {
        foreach (self::get_servives() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, "register")) {
                $service->register();
            }
        }
    }

    private static function instantiate($class)
    {
        return new $class();
    }
}
