<?php

namespace Uasoft\Badaso\Exceptions;

use Exception;

class SingleException extends Exception
{
    public function __construct($message)
    {
        $this->message = $message;
    }
}
