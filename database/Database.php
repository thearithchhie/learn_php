<?php

class Database
{
    private $DB_HOSTNAME = "localhost";
    private $DB_USERNAME = "root";
    private $DB_PORT = 3306;
    private $DB_PASSWORD = "";
    private $DB_NAME = "learn_todos";
    private $connection;

    public function __construct()
    {
       // init connection
       $this->connectionDB();
    }


    function connectionDB() {
       try {
        $this->connection = new mysqli(
            $this->DB_HOSTNAME,
            $this->DB_USERNAME,
            $this->DB_PASSWORD,
            $this->DB_NAME,
            $this->DB_PORT
        );
        if (mysqli_connect_error()) {
            throw new Exception("Connection failed: " . mysqli_connect_error());
        }
       } catch(Exception $e) {
        die("Database connection error: " . $e->getMessage());
       }
        return $this->connection;
    }

    // getter
    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        if($this->connection) {
            $this->connection->close(); 
        }
    }
}


