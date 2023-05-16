<?php

namespace Solvrtech\WPlogbook\Service\Logs;

use DateTime;
use Solvrtech\WPlogbook\Handler\LogHandler;
use Solvrtech\WPlogbook\Model\ClientModel;
use Solvrtech\WPlogbook\Model\LogModel;

class CriticalService
{
    protected $model;
    protected $client;

    public function __construct()
    {
        $this->model = new LogModel;
        $this->client = $_SERVER;
    }

    public function formatter(array $log, array $level_code): LogModel
    {
        switch ($log[1]) {
            case is_int(strpos($log[1], "PHP Parse error")):
                return $this->php_parse_error($log, $level_code);
                break;
            case is_int(strpos($log[1], "PHP Fatal error")):
                return $this->php_fatal_error($log, $level_code);
                break;
            default:
                break;
        }
    }

    public function php_parse_error(array $log, array $level_code): LogModel
    {
        $log_split = explode('@@@', str_replace('in /', '@@@', $log[1]));
        $file = $log_split[1];

        $log_split = explode('@@@', str_replace(':  ', '@@@', $log_split[0]));
        $message = $log_split[1];


        $this->model->setMessage($message)
            ->setFile($file)
            ->setLevel($level_code['LEVEL'])
            ->setCode($level_code['CODE'])
            ->setDateTime($log[0])
            ->setClient((new ClientModel)->setUrl($this->client['REQUEST_URI'])
                    ->setServer($this->client['SERVER_NAME'])
                    ->setHttpMethod($this->client['REQUEST_METHOD'])
                    ->setIp($this->client['REMOTE_ADDR'])
                    ->setUserAgent($this->client['HTTP_USER_AGENT'])
            );

        return $this->model;
    }

    public function php_fatal_error(array $log, array $level_code): LogModel
    {
        $log_split = explode('Stack trace:', $log[1]);

        $stacktrace_split = explode('{main}', preg_replace('/(#\d)|(")/', '', $log_split[1]));
        $stacktrace = explode('@@@', str_replace(' /', '@@@', $stacktrace_split[0]));
        $throw = str_replace('thrown in /', '', ltrim($stacktrace_split[1]));
        $final_stacktrace = array_merge($stacktrace, [$throw]);

        $log_split = explode('@@@', str_replace('in /', '@@@', $log_split[0]));
        $file = $log_split[1];

        $log_split = explode('@@@', str_replace(':  ', '@@@', $log_split[0]));
        $message = $log_split[1];

        $this->model->setMessage($message)
            ->setFile($file)
            ->setStackTrace(!empty($final_stacktrace) ? $final_stacktrace : null)
            ->setLevel($level_code['LEVEL'])
            ->setCode($level_code['CODE'])
            ->setDateTime($log[0])
            ->setClient((new ClientModel)->setUrl($this->client['REQUEST_URI'])
                    ->setServer($this->client['SERVER_NAME'])
                    ->setHttpMethod($this->client['REQUEST_METHOD'])
                    ->setIp($this->client['REMOTE_ADDR'])
                    ->setUserAgent($this->client['HTTP_USER_AGENT'])
            );

        return $this->model;
    }
}
