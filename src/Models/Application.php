<?php

namespace takisrs\Models;

use takisrs\Core\Model;

class Application extends Model
{
    public static string $tableName = 'applications';
    public static string $primaryKey = 'id';

    const STATUS_PENDING = 0;
    const STATUS_REJECTED = 1;
    const STATUS_APPROVED = 2;

    public $id;
    public $userId;
    public $dateFrom;
    public $dateTo;
    public $reason;
    public $status;

    public $createdAt;
    public $modifiedAt;

    public function find(int $id): ?Application
    {
        $query = "SELECT * FROM " . self::$tableName . " WHERE " . self::$primaryKey . " = " . $id;
        $result = $this->db->query($query);
        return $result->rowCount() === 1 ? $this->mapResultToObject($result->fetch()) : null;
    }

    public function create()
    {
        $query = "INSERT INTO " . self::$tableName . " (`userId`, `dateFrom`, `dateTo`, `reason`, `status`, `createdAt`) 
        VALUES ('" . $this->userId . "', '" . $this->dateFrom . "', '" . $this->dateTo . "', '" . $this->reason . "', '" . $this->status . "', '" . $this->createdAt . "')";

        $count = $this->db->exec($query);

        if ($count === 1) {
            $this->id = $this->db->lastInsertId();
            return $this;
        }

        return null;
    }

    public function update()
    {
        $statement = $this->db->prepare("UPDATE " . self::$tableName . " 
        SET 
            userId = :userId, 
            dateFrom = :dateFrom, 
            dateTo = :dateTo, 
            reason = :reason,
            status = :status
        WHERE
            id = :id");

        $statement->bindParam("userId", $this->userId);
        $statement->bindParam("dateFrom", $this->dateFrom);
        $statement->bindParam("dateTo", $this->dateTo);
        $statement->bindParam("reason", $this->reason);
        $statement->bindParam("id", $this->id);
        $statement->bindParam("status", $this->status);

        return $statement->execute();
    }

    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        return $this->update();
    }

    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
        return $this->update();
    }

    public function findAll(): array
    {
        $applicationObjects = [];
        $query = "SELECT * FROM " . self::$tableName;
        $result = $this->db->query($query);
        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                array_push($applicationObjects, $this->mapResultToObject($row));
            }
        }
        return $applicationObjects;
    }
}
