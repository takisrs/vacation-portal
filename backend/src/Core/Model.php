<?php

namespace takisrs\Core;

use takisrs\MySQLConnection;

/**
 * Base Controller class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Model implements ModelInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = MySQLConnection::getInstance()->getConnection();
    }

    public function find(int $id): ?object
    {
        $query = "SELECT * FROM " . get_called_class()::$tableName . " WHERE " . get_called_class()::$primaryKey . " = :id";
        $statement = $this->db->prepare($query);
        $statement->execute([':id' => $id]);
        return $statement->rowCount() === 1 ? $this->mapResultToObject($statement->fetch(\PDO::FETCH_ASSOC)) : null;
    }

    public function findOneBy(array $params): ?object
    {
        list($where, $bindParams) = $this->buildWhere($params);
        $query = "SELECT * FROM " . get_called_class()::$tableName . " WHERE " . $where;
        $statement = $this->db->prepare($query);
        $statement->execute($bindParams);
        return $statement->rowCount() === 1 ? $this->mapResultToObject($statement->fetch(\PDO::FETCH_ASSOC)) : null;
    }

    public function findBy(array $params): ?array
    {
        $objects = [];

        list($where, $bindParams) = $this->buildWhere($params);
        $query = "SELECT * FROM " . get_called_class()::$tableName . " WHERE " . $where;
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

    public function findAll(): array
    {
        $objects = [];

        $query = "SELECT * FROM " . get_called_class()::$tableName;
        $result = $this->db->query($query);
        foreach ($result as $row) {
            array_push($objects, $this->mapResultToObject($row));
        }
        return $objects;
    }

    public function create()
    {
        $values = [];
        foreach (get_called_class()::$fields as $field) {
            array_push($values, $this->$field);
        }
        echo "(" . implode(', ', get_called_class()::$fields) . ") VALUES (" . implode(', ', $values) . ")";
        //$query = "INSERT INTO ".get_called_class()::$tableName
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
        $bindParams = [];
        foreach ($params as $key => $value) {
            array_push($whereClause, $key . " = :" . $key);
            $bindParams[":" . $key] = $value;
        }
        return [implode(" AND ", $whereClause), $bindParams];
    }

    /**
     * Maps the result of a select query to the corresponding model
     *
     * @param array $record
     * @return object
     */
    protected function mapResultToObject($record): object
    {
        $objectClass = get_called_class();
        $object = new $objectClass;
        foreach ($record as $field => $value) {
            if (property_exists($object, $field))
                $object->$field = $value;
        }
        return $object;
    }
}
