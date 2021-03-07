<?php

namespace takisrs\Models;

use takisrs\Core\Model;

class User extends Model
{
    protected static string $tableName = 'users';
    protected static string $primaryKey = 'id';
    protected static array $fields = ["id", "firstName", "lastName", "email", "password", "type", "createdAt"];

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

    public function isAdmin()
    {
        return (int) $this->type === self::TYPE_ADMIN;
    }

    public function isUser()
    {
        return (int) $this->type === self::TYPE_USER;
    }

    public function create()
    {
        $query = "INSERT INTO " . self::$tableName . " (`firstName`, `lastName`, `email`, `password`, `type`, `createdAt`) 
        VALUES (:firstName, :lastName, :email, :password, :type, :createdAt)";

        $statement = $this->db->prepare($query);

        $result = $statement->execute([
            ":firstName" => $this->firstName,
            ":lastName" => $this->lastName,
            ":email" => $this->email,
            ":password" => $this->password,
            ":type" => $this->type,
            ":createdAt" => $this->createdAt
        ]);

        if ($result) {
            $this->id = $this->db->lastInsertId();
            return $this;
        }

        return null;
    }

    public function update()
    {
        $statement = $this->db->prepare("UPDATE " . self::$tableName . " 
        SET 
            firstName = :firstName, 
            lastName = :lastName, 
            email = :email, 
            password = :password,
            type = :type
        WHERE
            id = :id");

        $statement->bindParam("firstName", $this->firstName);
        $statement->bindParam("lastName", $this->lastName);
        $statement->bindParam("email", $this->email);
        $statement->bindParam("password", $this->password);
        $statement->bindParam("type", $this->type);
        $statement->bindParam("id", $this->id);

        return $statement->execute();
    }
}
