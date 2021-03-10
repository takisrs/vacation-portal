<?php

namespace takisrs\Core;

/**
 * Base Controller class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Controller
{
    /**
     * @var Request $request The request instance
     */
    protected $request;

    /**
     * @var Response $response The response instance
     */
    protected $response;

    /**
     * Base controller constructor
     * 
     * @param Request $request the request object
     * @param Response $response the response object
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
