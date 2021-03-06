<?php

namespace takisrs\Models;

use takisrs\Core\Model;

class User extends Model
{
    public static string $tableName = 'users';
    public static string $primaryKey = 'id';

    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $type;
    public $createdAt;
    public $modifiedAt;

    public function find(int $id): ?User
    {
        $query = "SELECT * FROM " . self::$tableName . " WHERE " . self::$primaryKey . " = " . $id;
        $result = $this->db->query($query);
        return $result->rowCount() == 1 ? $this->mapResultToObject($result->fetch()) : null;
    }

    public function findOneBy(array $params): ?User
    {
        $where = $this->buildWhere($params);
        $query = "SELECT * FROM users WHERE " . $where;
        $result = $this->db->query($query);
        if ($result->rowCount() > 0) {
            return $this->mapResultToObject($result->fetch());
        }

        return null;
    }

    public function findAll(): array
    {
        $userObjects = [];
        $query = "SELECT * FROM ".self::$tableName;
        $result = $this->db->query($query);
        foreach ($result as $row) {
            array_push($userObjects, $this->mapResultToObject($row));
        }
        return $userObjects;
    }
}
