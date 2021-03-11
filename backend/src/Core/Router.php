<?php

namespace takisrs\Core;

use takisrs\Core\HttpException;
use takisrs\Models\User;

/**
 * Router class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Router
{
    const REQUIRE_USER = 1;
    const REQUIRE_ADMIN = 2;

    /**
     * Keeps data about the available routes
     * 
     * @var array $routes
     */
    private array $routes = [];

    /** 
     * @var Request $request 
     */
    private Request $request;

    /** 
     * @var Response $response 
     */
    private Response $response;

    /**
     * Router constructor
     *
     * @param Request $request The instance of the Request
     * @param Response $response The instance of the Response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Checks if the request is authorized
     *
     * @param integer|null $accessLevel the access level to check against
     * @return void
     */
    private function requireAuth(?int $accessLevel = null): void
    {
        $token = $this->request->authorizationToken();
        if (!empty($token)) {
            $decodedData = Authenticator::decodeToken($token);
            if ($decodedData) {
                $userId = $decodedData["context"]->user->id;
                $user = (new User)->find($userId);
                if ($user) {
                    if (!empty($accessLevel) && $user->type != $accessLevel)
                        throw new HttpException(403, "Forbidden - Not authorized");
                    $this->request->setUser($user);
                }
            }
        }
        if ($this->request->user() == null) {
            throw new HttpException(401, "Not authenticated");
        }
    }

    /**
     * Registers a route
     *
     * @param string $method the request method
     * @param string $pattern the uri of the route
     * @param string $controller the controller class name
     * @param string $action the method of the controller to execute
     * @param integer $access the route's access level
     * @return void
     */
    private function addRoute(string $method, string $pattern, string $controller, string $action, int $access = 0): void
    {
        array_push($this->routes, [
            "method" => $method,
            "pattern" => $pattern,
            "controller" => $controller,
            "action" => $action,
            "access" => $access
        ]);
    }

    /**
     * Registers a post route
     *
     * @param string $pattern the uri of the route
     * @param string $controller the controller class name
     * @param string $action the method of the controller to execute
     * @param integer $access the route's access level
     * @return void
     */
    public function post(string $pattern, string $controller, string $method, int $access = 0): void
    {
        $this->addRoute("POST", $pattern, $controller, $method, $access);
    }

    /**
     * Registers a get route
     *
     * @param string $pattern the uri of the route
     * @param string $controller the controller class name
     * @param string $action the method of the controller to execute
     * @param integer $access the route's access level
     * @return void
     */
    public function get(string $pattern, string $controller, string $method, int $access = 0): void
    {
        $this->addRoute("GET", $pattern, $controller, $method, $access);
    }

    /**
     * Convert to regex
     *
     * @param array $matches
     * @return string
     */
    private function convertPatternToRegex(array $matches): string
    {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }

    /**
     * Checks if uri matches the pattern and extracts the params
     *
     * @param string $uri a uri
     * @param string $pattern a uri pattern
     * @return bool if uri matches the pattern or not
     */
    public function matchAndExtractParams(string $uri, string $pattern): bool
    {
        preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);

        $patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);

        if (substr($pattern, -1) === '/') {
            $patternAsRegex = $patternAsRegex . '?';
        }
        $patternAsRegex = '@^' . $patternAsRegex . '$@';

        if (preg_match($patternAsRegex, $uri, $paramsValue)) {

            array_shift($paramsValue);

            foreach ($params[0] as $value) {
                $val = substr($value, 1);
                if ($paramsValue[$val]) {
                    $this->request->setParam($val, urlencode($paramsValue[$val]));
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Matches the request with the corresponding controller/method and processes it
     *
     * @return void
     */
    public function run(): void
    {
        $uri = $this->request->getUri();
        $method = $this->request->getMethod();

        // if it is an options request sends 200 with the headers
        if ($method === "OPTIONS"){
            $this->response->status(200)->send("");
        }

        // matches the request method/uri with one of the available routes
        $matchedRoutes = array_filter($this->routes, function ($route) use ($method) {
            return $route["method"] == $method;
        });

        $matchedRoutes = array_filter($matchedRoutes, function ($route) use ($uri) {
            return $this->matchAndExtractParams($uri, $route["pattern"]);
        });

        $matchedRoute = reset($matchedRoutes);

        // if there is a match, checks if the authorized user has the required permissions and then executes the corresponding action (controller@method)
        if ($matchedRoute){
            if (isset($matchedRoute["access"]) && $matchedRoute["access"] > 0) {
                $this->requireAuth($matchedRoute["access"]);
            }
    
            $controller = new $matchedRoute["controller"]($this->request, $this->response);
    
            if (is_callable([$controller, $matchedRoute["action"]]))
                call_user_func([$controller, $matchedRoute["action"]]);
            else
                throw new HttpException(400, sprintf("Controller action not found %s@%s", $matchedRoute["controller"], $matchedRoute["action"]));
        } else {
            throw new HttpException(404, sprintf("No matching route for %s - %s", $uri, $method));
        }

    }
}
