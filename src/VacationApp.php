<?php

namespace takisrs;

use takisrs\Core\Request;
use takisrs\Core\Response;
use takisrs\Core\Router;

use takisrs\Controllers\AuthController;
use takisrs\Controllers\ApplicationController;
use takisrs\Controllers\UserController;

/**
 * App's main class
 * 
 * @author Panagiots Pantazopoulos <takispadaz@gmail.com>
 */
class VacationApp
{
    private $request;
    private $response;
    private $router;

    public function __construct()
    {
        $this->request = new Request;
        $this->response = new Response;
        $this->router = new Router($this->request, $this->response);
    }

    /**
     * Setup the required routes
     *
     * @return void
     */
    public function init()
    {
        // public routes
        $this->router->post("/auth/login", AuthController::class, 'login');

        // user routes
        $this->router->post("/applications", ApplicationController::class, 'create', Router::REQUIRE_AUTH);
        $this->router->get("/applications", ApplicationController::class, 'list', Router::REQUIRE_AUTH);

        // admin routes
        $this->router->get("/users", UserController::class, 'list');
        $this->router->post("/applications/:id/approve", ApplicationController::class, 'approve', Router::REQUIRE_ADMIN);
        $this->router->post("/applications/:id/reject", ApplicationController::class, 'reject', Router::REQUIRE_ADMIN);

        $this->router->run();
    }
}
