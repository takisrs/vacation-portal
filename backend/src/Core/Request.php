<?php

namespace takisrs\Core;

/**
 * Request class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Request
{
    private $authenticatedUser = null;
    private array $params = [];

    /**
     * sets the authentication user
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
