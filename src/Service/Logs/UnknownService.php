<?php

namespace Solvrtech\WPlogbook\Service\Logs;

use DateTime;
use Solvrtech\WPlogbook\Model\LogModel;
use Solvrtech\WPlogbook\Model\ClientModel;
use Solvrtech\WPlogbook\Handler\LogHandler;

class UnknownService
{
    public function formatter(array $log, array $level_code)
    {
        $model = new LogModel;
        if (strpos($log[1], '@@array@@'))
            $message = str_replace("@@array@@", "[]", $log[1]);
        else
            $message = $log[1];

        $clients = $_SERVER;
        $model->setMessage($message)
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
