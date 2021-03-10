<?php

namespace takisrs\Core;

/**
 * A simple HttpException class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class HttpException extends \Exception
{
    /**
     * HttpException constructor
     * 
     * @param int $code http status code
     * @param string $message an error message for the exception
     * @param \Throwable $previous
     */
    public function __construct(int $code = 500, string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
