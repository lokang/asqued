<?php
/**
 * Created by PhpStorm.
 * User: lokang
 * Date: 3/1/20
 * Time: 8:55 PM
 */
class Database{
    public $conn;
    public function __construct(){
        $servername = "localhost";
        if(in_array($_SERVER['SERVER_ADDR'], array('localhost', '127.0.0.1', '::1'))){
            $dbname = "askqued";
            $username = "root";
            $password = "root";
        }else{
            $dbname = "lokancpn_lokang";
            $username = "lokancpn";
            $password = "Lokang21#@!";
        }try{
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            //echo "Connected successfully";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function prepare($prepare){
        return $this->conn->prepare($prepare);
    }
}