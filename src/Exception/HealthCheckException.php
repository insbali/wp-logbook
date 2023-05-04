<?php

namespace Solvrtech\WPlogbook\Exception;

use Exception;
use Throwable;

class HealthCheckException extends Exception
{
    public function __construct(
        $message = "Health check was failed",
        $code = 500,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
