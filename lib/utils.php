<?php
require_once __PATH__ . "/lib/connections.php";

class db{
    public static function fetchOne($query){
        $connection = new Connection();
        $pdo = $connection->getConnection();
        
        try {
            $statement = $pdo->query($query);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        
        return $statement->fetchObject();
    }
    
    public static function fetchAll($query){
        $connection = new Connection();
        $pdo = $connection->getConnection();
        
        try {
            $statement = $pdo->query($query);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        
        $data = [];
        while ($object = $statement->fetchObject()) {
            $data [] = $object;
        }

        return $data;
    }
    
    public static function getConnection(){
        $connection = new Connection();
        
        return $connection->getConnection();
    }
    
    public static function insert($table, array $data){
        $connection = new Connection();
        $pdo = $connection->getConnection();
        
        try {
            $keys = $values = $keyParams = array();
            
            foreach ($data as $key => $value) {
                $keyParam           = ":{$key}";
                $keyParams[]        = $keyParam;
                $keys[]             = $key;
                $values[$keyParam]  = $value;
            }
            
            $sql = "INSERT INTO {$table} (" . implode(",", $keys) . ") VALUES (" . implode(",", $keyParams) . ")";
            
            $query = $pdo->prepare($sql);
            
            return $query->execute($values);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    public static function delete($table, array $data){
        $connection = new Connection();
        $pdo = $connection->getConnection();
        
        try {
            $values = $whereString = array();
            
            foreach ($data as $key => $value) {
                $keyParam           = ":{$key}";
                $whereString[]      = "{$key} = {$keyParam}";
                $values[$keyParam]  = $value;
            }
            
            $sql = "DELETE FROM {$table} WHERE " . implode(" AND ", $whereString);
            
            $query = $pdo->prepare($sql);
            
            return $query->execute($values);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    public static function update($table, array $data, array $conditions){
        $connection = new Connection();
        $pdo = $connection->getConnection();
        
        try {
            $setString = $whereString = array();
            
            foreach ($data as $key => $value) {
                $keyParam           = ":{$key}";
                $setString[]        = "{$key} = {$keyParam}";
                $values[$keyParam]  = $value;
            }
            
            foreach ($conditions as $key => $value) {
                $keyParam           = ":{$key}";
                $whereString[]      = "{$key} = {$keyParam}";
                $values[$keyParam]  = $value;
            }
            
            $sql = "UPDATE {$table} SET " . implode(",", $setString) . " WHERE " . implode(" AND ", $whereString);
            
            $query = $pdo->prepare($sql);
            
            return $query->execute($values);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}

class file{
    public static function getComponentsOfDir($path){
        return array_diff(scandir($path), [".", ".."]);
    }
    
    public static function createDir($path){
        return mkdir($path, 0777, true);
    }
}

class string{
    public static function crypt($string) {
        return crypt($string, '$2a$09$tARm1a9A9N7q1W9T9n5LqR$');
    }
}

class app{
    public static function vd($expresion) {
        echo "<pre>";
        var_dump($expresion);
        echo "</pre>";
    }
}