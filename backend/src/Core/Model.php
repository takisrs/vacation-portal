<?php

namespace takisrs\Core;

use takisrs\Core\MySQLConnection;

/**
 * Base Controller class
 * 
 * Any model extends this class.
 * Provides some methods that simplify SELECT, CREATE and UPDATE sql queries.
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Model
{
    /**
     * An instance of the mysql database PDO connection
     *
     * @var \PDO
     */
    protected \PDO $db;

    public function __construct()
    {
        $this->db = MySQLConnection::getInstance()->getConnection();
    }

    /**
     * Accepts an id and retrieves the corresponding record from the database
     *
     * @param integer $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        $query = "SELECT * FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = :id";
        $statement = $this->db->prepare($query);
        $statement->execute([':id' => $id]);
        return $statement->rowCount() === 1 ? $this->mapResultToObject($statement->fetch(\PDO::FETCH_ASSOC), $this) : null;
    }

    /**
     * Retrieves a record from the database
     *
     * @param array $params
     * @return object|null
     */
    public function findOneBy(array $params): ?object
    {
        list($where, $bindParams) = $this->buildWhere($params);
        $query = "SELECT * FROM " . $this->tableName . " WHERE " . $where;
        $statement = $this->db->prepare($query);
        $statement->execute($bindParams);
        return $statement->rowCount() === 1 ? $this->mapResultToObject($statement->fetch(\PDO::FETCH_ASSOC)) : null;
    }

    /**
     * Retrieves a list of records of the database and return an object for each record
     *
     * @param array $params
     * @param string $sort sql ordering option
     * @return array|null array of objects
     */
    public function findBy(array $params, ?string $sort = null): ?array
    {
        $objects = [];

        list($where, $bindParams) = $this->buildWhere($params);
        $query = "SELECT * FROM " . $this->tableName . " WHERE " . $where . (isset($sort) ? " ORDER BY " . $sort : "");
        $statement = $this->db->prepare($query);
        $statement->execute($bindParams);
        if ($statement->rowCount() > 0) {
            foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $result) {
                array_push($objects, $this->mapResultToObject($result));
            }
            return $objects;
        }

        return null;
    }

    /**
     * Retrieves all the records of the corresponding table from the database
     *
     * @param string $sort sql ordering option
     * @return array
     */
    public function findAll(?string $sort = null): array
    {
        $objects = [];

        $query = "SELECT * FROM " . $this->tableName . (isset($sort) ? " ORDER BY " . $sort : "");
        $result = $this->db->query($query);
        foreach ($result as $row) {
            array_push($objects, $this->mapResultToObject($row));
        }
        return $objects;
    }

    /**
     * Performs an insert query
     *
     * @return object|null
     */
    public function create(): ?object
    {
        $bindParams = $this->getBindParams();

        $query = "INSERT INTO " . $this->tableName . "(" . implode(', ', $this->fillable) . ") 
        VALUES (" . implode(', ', array_keys($bindParams)) . ")";

        $statement = $this->db->prepare($query);

        $result = $statement->execute($bindParams);

        if ($result) {
            $this->id = $this->db->lastInsertId();
            return $this;
        }

        return null;
    }

    /**
     * Performs an update query
     *
     * @return bool
     */
    public function update(): bool
    {
        $setParams = [];
        foreach ($this->fillable as $field) {
            array_push($setParams, $field . ' = :' . $field);
        }

        $query = "UPDATE " . $this->tableName . " SET " . implode(", ", $setParams) . " WHERE " . $this->primaryKey . " = " . $this->{$this->primaryKey};

        $statement = $this->db->prepare($query);

        return $statement->execute($this->getBindParams());
    }

    /**
     * Returns an array with the pdo bindings
     *
     * @return array
     */
    protected function getBindParams(): array
    {
        $bindParams = [];
        foreach ($this->fillable as $field) {
            $bindParams[':' . $field] = $this->$field;
        }
        return $bindParams;
    }

    /**
     * Gets and array of field-value pairs and returns an sql where clause
     *
     * @param array $params field-value pairs
     * @return array sql where clause and bind params
     */
    protected function buildWhere($params): array
    {
        $whereClause = [];
        $whereBindParams = [];
        foreach ($params as $key => $value) {
            array_push($whereClause, $key . " = :" . $key);
            $whereBindParams[":" . $key] = $value;
        }
        return [implode(" AND ", $whereClause), $whereBindParams];
    }

    /**
     * Maps the result of a select query to the corresponding model
     *
     * @param array $record
     * @return object
     */
    protected function mapResultToObject($record, ?object $object = null): object
    {
        if (!$object) {
            $objectClass = get_called_class();
            $object = new $objectClass;
        }
        foreach ($record as $field => $value) {
            if (property_exists($object, $field))
                $object->$field = $value;
        }
        return $object;
    }
}
