<?php

namespace Solvrtech\WPlogbook;

if (!defined("ABSPATH")) {
    exit;
}

use Solvrtech\WPlogbook\Admin\AdminPageSetting;
use Solvrtech\WPlogbook\Admin\AdminSetting;
use Solvrtech\WPlogbook\Controller\LogsController;
use Solvrtech\WPlogbook\Controller\HealthController;
use Solvrtech\WPlogbook\Model\LogModel;

final class Init
{
    /**
     * Undocumented function
     *
     * @return string[]
     */
    private static function get_servives()
    {
        return [
            AdminPageSetting::class,
            HealthController::class,
            LogsController::class,
        ];
    }

    /**
     * Instantiate the all class on get_services method
     *
     * @return void
     */
    public static function register_services()
    {
        foreach (self::get_servives() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, "register")) {
                $service->register();
            }
        }
    }

    /**
     * Instance of class
     *
     * @param mixed $class
     * @return object
     */
    private static function instantiate($class)
    {
        return new $class();
    }
}
