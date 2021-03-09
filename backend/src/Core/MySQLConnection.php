<?php

namespace takisrs\Core;

/**
 * A class that handles the mysql connection following the singleton pattern
 * 
 * @author Panagiotis Pantazopoulos <takispadaz@gmail.com>
 */
class MySQLConnection
{
    private static $instance = null;
    private $connection;

    public function __construct()
    {
        $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];
        $conn = new \PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);

        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->connection = $conn;
    }

    /**
     * Return the instance of the MySQLConnection
     *
     * @return MySQLConnection
     */
    public static function getInstance(): MySQLConnection
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Return the PDO connection
     *
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}
