<?php

namespace Solvrtech\WPlogbook\Check;

if (!defined("ABSPATH")) {
    exit;
}

use Exception;
use Solvrtech\WPlogbook\Model\HealthCheckModel;
use Solvrtech\WPlogbook\Exception\HealthCheckException;

class DatabaseCheck
{
    public static function get_key()
    {
        return 'database';
    }

    public function run(): HealthCheckModel
    {
        $health_model = new HealthCheckModel;

        try {

            $db_size = self::check();

            $health_model->set_status(HealthCheckModel::OK)
                ->set_meta([
                    "databaseSize" => [
                        "default" => $db_size
                    ],
                    "unit" => "Mb"
                ]);
        } catch (Exception $exception) {
            // ...
        }

        return $health_model->set_key(self::get_key());
    }

    private static function check(): float
    {
        global $wpdb;
        $db_check_connection = $wpdb->db_connect();
        $db_name = DB_NAME;

        if (!$db_check_connection)
            throw new HealthCheckException();

        try {
            $result = $wpdb->get_results("SELECT table_schema '{$db_name}', ROUND(SUM(data_length + index_length) / 1048576, 2) as size FROM information_schema.tables GROUP BY table_schema");

            return number_format((float)array_sum(array_column($result, 'size')), 2, '.', '');
        } catch (Exception $exception) {
            throw new HealthCheckException();
        }
    }
}
