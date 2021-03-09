<?php

namespace takisrs\Models;

use takisrs\Core\Model;

class Application extends Model
{
    protected string $tableName = 'applications';
    protected string $primaryKey = 'id';
    protected array $fillable = ["userId", "dateFrom", "dateTo", "reason", "status", "createdAt"];

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

    /*
    public function update()
    {
        $statement = $this->db->prepare("UPDATE " . $this->tableName . " 
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
*/
    public function user()
    {
        return (new User())->find($this->userId);
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
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

    public function days()
    {
        $dateFrom = new \DateTime($this->dateFrom);
        $dateTo = new \DateTime($this->dateTo);
        return $dateTo->diff($dateFrom)->format("%a");
    }
}
