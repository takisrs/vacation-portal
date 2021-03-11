<?php

namespace takisrs\Core;

/**
 * Base Controller class
 * 
 * Each controller should extend this class.
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Controller
{
    /**
     * The request instance
     * 
     * @var Request $request
     */
    protected Request $request;

    /**
     * The response instance
     * 
     * @var Response $response
     */
    protected Response $response;

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
