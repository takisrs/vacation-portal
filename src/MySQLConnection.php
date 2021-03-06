<?php

namespace takisrs;

class MySQLConnection implements DBConnectionInterface
{
    private static $instance = null;
    private $connection;

    public function __construct($dbhost = 'db', $dbuser = 'epignosis', $dbpass = 'epignosis', $dbname = 'vacation', $charset = 'utf8')
    {
        try {
            $dsn = "mysql:host=" . $dbhost . ";dbname=" . $dbname . ";charset=" . $charset;
            $conn = new \PDO($dsn, $dbuser, $dbpass);

            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $this->connection = $conn;
        } catch (\PDOException $e) {
            $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($query, $params = [])
    {
        return $this->connection->query($query);
    }
}
