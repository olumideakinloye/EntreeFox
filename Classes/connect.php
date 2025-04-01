<?php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "entreefox_db";


    public function connect()
    {
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        return $connection;
    }
    public function save($query)
    {
        $con = $this->connect();
        $result = mysqli_query($con, $query);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
    public function read($query)
    {
        $con = $this->connect();
        $result = mysqli_query($con, $query);
        if (!$result) {
            return false;
        } else {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row; // Fetch each row as an associative array
            }
            return $data;
        }
    }
}
