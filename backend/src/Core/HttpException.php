<?php

namespace takisrs\Core;

class HttpException extends \Exception
{
    public function __construct(int $code = 500, string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
