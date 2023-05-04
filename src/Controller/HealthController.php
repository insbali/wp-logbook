<?php

namespace Solvrtech\WPlogbook\Controller;

if (!defined("ABSPATH")) {
    exit;
}

use Solvrtech\WPlogbook\Service\HealthService;

class HealthController
{
    public function register()
    {
        add_action('rest_api_init', array($this, 'register_health_check_route'));
    }

    public function register_health_check_route()
    {
        register_rest_route(
            'wp-logbook',
            '/logbook-health',
            array(
                "methods" => "GET",
                "callback" => array($this, "health_check_data"),
                "permission_callback" => function ($requests) {
                    if ($requests->get_header('x_logbook_key') !== get_option('wp_logbook_api_key'))
                        return false;

                    return true;
                }
            )
        );
    }

    public function health_check_data()
    {
        $service = new HealthService;
        return rest_ensure_response(
            $service->get_health_response()
        );
    }
}
