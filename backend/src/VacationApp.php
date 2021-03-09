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
     * Declares the routes that the app uses
     *
     * @return void
     */
    public function registerRoutes(): void
    {
        // public routes
        $this->router->post("/auth/login", AuthController::class, 'login');

        // user routes
        $this->router->post("/applications", ApplicationController::class, 'create', Router::REQUIRE_USER);
        $this->router->get("/applications", ApplicationController::class, 'list', Router::REQUIRE_USER);

        // admin routes
        $this->router->get("/users", UserController::class, 'list', Router::REQUIRE_ADMIN);
        $this->router->post("/users", UserController::class, 'create', Router::REQUIRE_ADMIN);
        $this->router->get("/users/:id", UserController::class, 'single', Router::REQUIRE_ADMIN);
        $this->router->post("/users/:id", UserController::class, 'update', Router::REQUIRE_ADMIN);
        $this->router->post("/applications/:id/approve", ApplicationController::class, 'approve', Router::REQUIRE_ADMIN);
        $this->router->post("/applications/:id/reject", ApplicationController::class, 'reject', Router::REQUIRE_ADMIN);
    }

    /**
     * App's main method. All starts here.
     * 
     * It wraps all the execution in a try/catch block to catch any unhandled exeption and return a meaningful json response to the client.
     *
     * @return void
     */
    public function init()
    {
        try {
            $this->registerRoutes();
            $this->router->run();
        } catch (\takisrs\Core\HttpException $e){
            $this->response->status($e->getCode())->send([
                "ok" => false,
                "message" => $e->getMessage()
            ]);
        } catch (\Exception|\Error $e) {
            $this->response->status(500)->send([
                "ok" => false,
                //we may exclude the exception message in the response for security reasons
                "message" => sprintf("Error Occured: %s", $e->getMessage())
            ]);
        }
    }
}
