<?php

namespace takisrs\Controllers;

use takisrs\Core\Controller;

use takisrs\Models\User;

/**
 * User Controller
 */
class UserController extends Controller
{

    /**
     * Retrieves and responses with the list of all users
     *
     * @return void
     */
    public function list(): void
    {
        $user = new User;
        $users = $user->findAll();
        
        if (count($users) > 0){
            $this->response->status(200)->send([
                "status" => true,
                "message" => sprintf("Retrieved %d users", count($users)),
                "data" => [
                    "users" => $users
                ]
            ]);
        }
    }
}
