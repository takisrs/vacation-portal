<?php

namespace takisrs\Controllers;

use takisrs\Core\Controller;
use takisrs\Core\Authenticator;

use takisrs\Models\User;

/**
 * Auth Controller
 * 
 * Handles the requests regarding authentication, such as login, signup. Currently, only the login method has been implemented
 */
class AuthController extends Controller
{

    /**
     * Login
     *
     * @return void
     */
    public function login(): void
    {
        $user = new User;
        $user = $user->findOneBy([
            'email' => $this->request->body('email'),
            'password' => md5($this->request->body('password'))
        ]);

        if ($user) {
            $token = Authenticator::getToken([
                "iss" => "Panos",
                "iat" => time(),
                "exp" => time() + 60 * 60,
                "context" => [
                    "user" => [
                        "id" => $user->id,
                        "email" => $user->email,
                        "type" => $user->type
                    ]
                ]
            ]);
            $this->response->status(200)->send([
                "ok" => true,
                "message" => "You logged in successfully",
                "data" => [
                    "token" => $token,
                    "user" => $user
                ]
            ]);
        } else {
            $this->response->status(401)->send([
                "ok" => false,
                "message" => "Login failed. Wrong username or password"
            ]);
        }
    }
}
