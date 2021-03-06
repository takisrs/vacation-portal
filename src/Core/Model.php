<?php

namespace takisrs\Core;

use takisrs\DBConnectionInterface;
use takisrs\MySQLConnection;

/**
 * Base Controller class
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class Model implements ModelInterface
{
    public static string $tableName = "";
    public static string $primaryKey = "id";

    protected $db;

    public function __construct()
    {
        $this->db = MySQLConnection::getInstance()->getConnection();
    }

    /**
     * Gets and array of field-value pairs and returns an sql where clause
     *
     * @param array $params field-value pairs
     * @return string sql where clause
     */
    protected function buildWhere($params)
    {
        $whereClause = [];
        foreach ($params as $key => $value) {
            array_push($whereClause, $key . " = " . $this->db->quote($value));
        }
        return implode(" AND ", $whereClause);
    }

    /**
     * Maps the result of a select query to the corresponding model
     *
     * @param array $record
     * @return $this
     */
    protected function mapResultToObject($record)
    {
        foreach ($record as $field => $value) {
            if (property_exists($this, $field))
                $this->$field = $value;
        }
        return $this;
    }
}
