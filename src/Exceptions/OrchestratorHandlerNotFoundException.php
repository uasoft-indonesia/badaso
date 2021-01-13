<?php

namespace Uasoft\Badaso\Exceptions;

use Exception;
use Throwable;

class OrchestratorHandlerNotFoundException extends Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
