<?php

namespace Solvrtech\WPlogbook\Service\Logs;

use Solvrtech\WPlogbook\Model\LogModel;
use Solvrtech\WPlogbook\Model\ClientModel;
use Solvrtech\WPlogbook\Handler\LogHandler;

class WarningService
{
    public function formatter(array $log, array $level_code)
    {
        $model = new LogModel;

        $log_split = explode('@@@', str_replace('in /', '@@@', $log[1]));
        $file = $log_split[1];

        $log_split = explode('@@@', str_replace(':  ', '@@@', $log_split[0]));
        $message = $log_split[1];

        $clients = $_SERVER;
        $model->setMessage($message)
            ->setFile($file)
            ->setLevel($level_code['LEVEL'])
            ->setCode($level_code['CODE'])
            ->setDateTime($log[0])
            ->setClient((new ClientModel)->setUrl($clients['REQUEST_URI'])
                ->setServer($clients['SERVER_NAME'])
                ->setHttpMethod($clients['REQUEST_METHOD'])
                ->setIp($clients['REMOTE_ADDR'])
                ->setUserAgent($clients['HTTP_USER_AGENT']));

        return $model;
    }
}
