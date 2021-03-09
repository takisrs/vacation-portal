<?php

namespace takisrs\Models;

use takisrs\Core\Model;

/**
 * User Model class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class User extends Model
{
    protected string $tableName = 'users';
    protected string $primaryKey = 'id';
    protected array $fillable = ["firstName", "lastName", "email", "password", "type", "createdAt"];

    const TYPE_USER = 1;
    const TYPE_ADMIN = 2;

    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $type;
    public $createdAt;
    public $modifiedAt;

    /**
     * Returns true if the user is an admin
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return (int) $this->type === self::TYPE_ADMIN;
    }

    /**
     * Returns true if the user is a simple user
     *
     * @return boolean
     */
    public function isUser(): bool
    {
        return (int) $this->type === self::TYPE_USER;
    }

    /**
     * Returns an array of user's applications
     *
     * @return Application[]
     */
    public function applications(): array
    {
        $applications = new Application;
        return $applications->findBy(["userId" => $this->id]);
    }
}
