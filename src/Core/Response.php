<?php

namespace takisrs\Core;

/**
 * Response class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Response
{
    private int $code;

    /**
     * Sets the response status code
     * 
     * @param int $code status code
     * @return Response
     */
    public function status($code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Send a response to the client
     *
     * @param array $data an array with the data of the response
     * @return void
     */
    public function send($data): void
    {
        $this->headers();
        echo json_encode($data);
        exit;
    }

    /**
     * The headers of the response
     *
     * @return void
     */
    private function headers(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($this->code);
    }

}
