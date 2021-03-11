<?php

namespace takisrs\Core;

/**
 * Request class
 * 
 * Helps with the request manipullation.
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Request
{
    /**
     * The object of the authenticated user
     * 
     * @var \takisrs\Models\User|null $authenticatedUser
     */
    private ?\takisrs\Models\User $authenticatedUser = null;

    /**
     * Keeps url parameters
     * 
     * @var array $params
     */
    private array $params = [];

    /**
     * Sets the authentication user
     *
     * @param \takisrs\Models\User $user a user's object
     * @return Request
     */
    public function setUser(\takisrs\Models\User $user): self
    {
        $this->authenticatedUser = $user;
        return $this;
    }

    /**
     * Returns the authorized user of the request
     *
     * @return \takisrs\Models\User
     */
    public function user(): ?\takisrs\Models\User
    {
        return $this->authenticatedUser;
    }

    /**
     * Sets a route param
     *
     * @param string $key
     * @param string|int $value
     * @return Request
     */
    public function setParam(string $key, string $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Retrieves a param from the request according to the provided key
     *
     * @param string $key
     * @return mixed
     */
    public function param(?string $key = null)
    {
        if (!empty($key))
            return isset($this->params[$key]) ? $this->clean($this->params[$key]) : null;

        return  $this->clean($this->params);
    }

    /**
     * Retrieves the authorization token of the request
     *
     * @return string|null
     */
    public function authorizationToken(): ?string
    {
        $token = $this->header('Authorization');
        if (!empty($token)) {
            $tokenParts = explode(" ", $token);
            return isset($tokenParts[1]) ? $tokenParts[1] : null;
        }
        return null;
    }

    /**
     * Retrieves a header from the request
     *
     * @param string|null $key header name
     * @return mixed header value
     */
    public function header(?string $key = null)
    {
        $headers = apache_request_headers();

        if (!empty($key))
            return isset($headers[$key]) ? $this->clean($headers[$key]) : null;

        return  $this->clean($headers);
    }

    /**
     *  Retrieves a $_GET parameter
     *
     * @param String $key
     * @return mixed
     */
    public function get(?string $key = null)
    {
        if (!empty($key))
            return isset($_GET[$key]) ? $this->clean($_GET[$key]) : null;

        return $this->clean($_GET);
    }

    /**
     *  Retrieves a $_POST parameter
     *
     * @param String $key
     * @return mixed
     */
    public function post(?string $key = null)
    {
        if (!empty($key))
            return isset($_POST[$key]) ? $this->clean($_POST[$key]) : null;

        return  $this->clean($_POST);
    }

    /**
     * Retrieves a parameter from the json request body
     *
     * @param string|null $key
     * @return mixed
     */
    public function body(?string $key = null)
    {
        $postdata = file_get_contents("php://input");
        $body = json_decode($postdata, true);

        if (!empty($key)) {
            return isset($body[$key]) ? $this->clean($body[$key]) : null;
        }

        return $body;
    }

    /**
     *  Returns the request method (POST, GET)
     *
     * @return string the request method
     */
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     *  Returns the request uri
     *
     * @return string the uri
     */
    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * A simple validation method for all request params (get, post, body)
     *
     * @param array $rulesArr
     * @return bool|null
     */
    public function validate(array $rulesArr): ?bool
    {
        foreach ($rulesArr as $field => $rules) {
            list($method, $field) = explode(".", $field);

            $value = call_user_func([$this, $method], $field);

            foreach ($rules as $rule) {
                switch ($rule) {
                    case "required":
                        if (empty($value))
                            throw new HttpException(400, sprintf("The parameter %s is required", $field));
                        break;
                    case "email":
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            throw new HttpException(400, sprintf("The parameter %s should be a valid email address", $field));
                        }
                        break;
                    case "date":
                        if (\DateTime::createFromFormat('Y-m-d', $value) == false) {
                            throw new HttpException(400, sprintf("The parameter %s should be a valid date with the format Y-m-d", $field));
                        }
                }
            }
        }

        return true;
    }

    /**
     * Cleans the provided data
     *
     * @param mixed $data
     * @return mixed
     */
    private function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }
}
