<?php 

class Database{
    private static $db="Ecommerce";
    private static $user="admin";
    private static $pass="admin";
    private static $conn;

    static function connect(){
        try{
            $conn = new PDO("mysql:dbname=".self::$db,self::$user,self::$pass,[
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]);
            return $conn;
        }catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    static function sql($query){
        // try to avoid SQL Injection
        // but depends mostly of developer
        return self::connect()->prepare($query);
    }

    static function sqlFor($query){
        $query=self::connect()->prepare($query);
        $query->execute();
        return $query;
    }

}

?>
