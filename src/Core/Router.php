<?php

namespace takisrs\Core;

use takisrs\Models\User;

/**
 * Response class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Router
{
    const REQUIRE_AUTH = 1;
    const REQUIRE_ADMIN = 2;

    private array $routes = [];
    private $request;
    private $response;

    /**
     * Router constructor
     *
     * @param \takisrs\Core\Request $request
     * @param \takisrs\Core\Response $response
     */
    public function __construct(\takisrs\Core\Request $request, \takisrs\Core\Response $response)
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
        try {
            $token = $this->request->authorizationToken();
            if (!empty($token)) {
                $decodedData = Authenticator::decodeToken($token);
                if ($decodedData) {
                    $userId = $decodedData["context"]->user->id;
                    $user = new User;
                    $user = $user->find($userId);
                    if ($user) {
                        if (!empty($accessLevel) && $user->type != $accessLevel)
                            throw new \Exception("Not authenticated");
                        $this->request->setUser($user);
                    }
                }
            }
            if ($this->request->user() == null) {
                throw new \Exception("Not authenticated");
            }
        } catch (\Exception $e) {
            $this->response->status(401)->send([
                "status" => false,
                "message" => "Not authenticated",
                "data" => $e->getMessage()
            ]);
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

        $matchedRoutes = array_filter($this->routes, function ($route) use ($method) {
            return $route["method"] == $method;
        });

        $matchedRoutes = array_filter($matchedRoutes, function ($route) use ($uri) {
            return $this->matchAndExtractParams($uri, $route["pattern"]);
        });

        $matchedRoute = reset($matchedRoutes);
        $params = [];

        if ($matchedRoute["access"] > 0) {
            $this->requireAuth($matchedRoute["access"]);
        }

        $controller = new $matchedRoute["controller"]($this->request, $this->response);

        if (is_callable([$controller, $matchedRoute["action"]]))
            call_user_func([$controller, $matchedRoute["action"]], $params);
        else
            $this->response->status(404)->send([
                "status" => false,
                "message" => "Controller action not found " . $matchedRoute["controller"] . "@" . $matchedRoute["action"]
            ]);
    }
}
