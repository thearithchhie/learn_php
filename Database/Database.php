<?php
class Database {
    private $concetion;
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "crud_with_ajax";
    private $port = "3306"; // defalut port of MYSQL 

    public function __construct() {
        $this->initDB();
    }

    // init DB
    private function initDB() {
       try {
            $this->concetion = mysqli_connect(
                $this->hostname,
                $this->username,
                $this->password,
                $this->database,
                $this->port
            );
            if(mysqli_connect_error()) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }

       }catch(Exception $e) {
         throw new Exception("something when wrong with init DB". $e->getMessage());
       }
       return $this->concetion;
    }

    public function getConnection() {
        return $this->concetion;
    }

    public function closeConnection() {
        if($this->concetion) {
            mysqli_close($this->concetion);
        }
    }

}

