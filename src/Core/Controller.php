<?php

namespace takisrs\Core;

/**
 * Base Controller class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Controller
{
    protected $request;
    protected $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
