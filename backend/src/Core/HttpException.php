<?php

namespace takisrs\Core;

/**
 * A simple HttpException class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class HttpException extends \Exception
{
    public function __construct(int $code = 500, string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
