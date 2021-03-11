<?php

namespace takisrs\Models;

use takisrs\Core\Model;

/**
 * Application Model class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Application extends Model
{
    /**
     * The name of aplications' table
     * 
     * @var string $tableName
     */
    protected string $tableName = 'applications';

    /**
     * The primary key of aplications table
     * 
     * @var string $primaryKey
     */
    protected string $primaryKey = 'id';

    /**
     * The fields that can be filled in the aplications' database table
     * 
     * @var string $fillable
     */
    protected array $fillable = ["userId", "dateFrom", "dateTo", "reason", "status", "createdAt"];

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public $id;
    public $userId;
    public $dateFrom;
    public $dateTo;
    public $reason;
    public $status;

    public $createdAt;
    public $modifiedAt;

    /**
     * Returns application's user object
     *
     * @return User
     */
    public function user(): User
    {
        return (new User())->find($this->userId);
    }

    /**
     * Returns true if the application has been approved
     *
     * @return boolean
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Returns true if the application has been rejected
     *
     * @return boolean
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approves the application (Updates status to "approved")
     *
     * @return boolean
     */
    public function approve(): bool
    {
        $this->status = self::STATUS_APPROVED;
        return $this->update();
    }

    /**
     * Rejects the application (Updates status to "rejected")
     *
     * @return boolean
     */
    public function reject(): bool
    {
        $this->status = self::STATUS_REJECTED;
        return $this->update();
    }

    /**
     * Returns the duration of the vacation in days
     *
     * @return integer
     */
    public function days(): int
    {
        $dateFrom = new \DateTime($this->dateFrom);
        $dateTo = new \DateTime($this->dateTo);
        return (int) $dateTo->diff($dateFrom)->format("%a") + 1;
    }
}
