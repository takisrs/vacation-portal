<?php

namespace takisrs\Controllers;

use Exception;
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
                "ok" => true,
                "message" => sprintf("Retrieved %d users", count($users)),
                "data" => [
                    "users" => $users
                ]
            ]);
        }
    }

    /**
     * Retrieves and responses with the fields of the requested user
     *
     * @return void
     */
    public function single(): void
    {
        $user = new User;
        $user = $user->find($this->request->param("id"));
        
        if (!$user)
            throw new Exception(sprintf("User with id %d not found", $this->request->param("id")));

        $this->response->status(200)->send([
            "ok" => true,
            "message" => sprintf("User with id %d retrieved", $user->id),
            "data" => [
                "user" => $user
            ]
        ]);

    }

    /**
     * Creates a new user
     *
     * @return void
     */
    public function create(): void
    {
        try {
            $user = new User;
            $user->firstName = $this->request->body('firstName');
            $user->lastName = $this->request->body('lastName');
            $user->email = $this->request->body('email');
            $user->password = md5($this->request->body('password'));
            $user->type = $this->request->body('type');
            $user->createdAt = (new \DateTime('now'))->format("Y-m-d H:i:s");

            $user = $user->create();

            if ($user) {
                $this->response->status(200)->send([
                    "ok" => true,
                    "message" => "User created successfully",
                    "data" => [
                        "user" => $user
                    ]
                ]);
            } else {
                throw new \Exception("User not created");
            }
        } catch (\Exception $e) {
            $this->response->status(401)->send([
                "ok" => false,
                "message" => sprintf("User's creation failed: %s", $e->getMessage())
            ]);
        }
    }


    /**
     * Updates an user
     *
     * @return void
     */
    public function update(): void
    {
        try {
            $user = new User;
            $user = $user->find($this->request->param("id"));

            $firstName = $this->request->body("firstName");
            $lastName = $this->request->body("lastName");
            $email = $this->request->body("email");
            $password = $this->request->body("password");
            $type = $this->request->body("type");
            
            if ($firstName) $user->firstName = $firstName;
            if ($lastName) $user->lastName = $lastName;
            if ($email) $user->email = $email;
            if ($password) $user->password = md5($password);
            if ($type) $user->type = $type;

            if (!isset($user)) throw new \Exception("User not found");

            $user->update();
            
            $this->response->status(200)->send([
                "ok" => true,
                "message" => "User updated",
                "data" => [
                    "user" => $user
                ]
            ]);
        } catch (\Exception $e) {
            $this->response->status(401)->send([
                "ok" => true,
                "message" => sprintf("User update failed: %s", $e->getMessage())
            ]);
        }

    }
}
