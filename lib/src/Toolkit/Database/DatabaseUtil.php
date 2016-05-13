<?php

namespace Toolkit\Database;

use PDOException;
use Toolkit\Database\Connection;

class DatabaseUtil {
    protected $connection;
    
    final public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function fetchOne($query){
        $pdo = $this->connection->getConnection();
        
        $pdoStatement = $pdo->query($query);
        
        return $pdoStatement->fetchObject();
    }
    
    public function fetchAll($query){
        $pdo = $this->connection->getConnection();
        
        $pdoStatement = $pdo->query($query);
        
        $data = [];
        
        while ($object = $pdoStatement->fetchObject()) {
            $data [] = $object;
        }

        return $data;
    }
    
    public function insert($table, array $data){
        $return = [
            "success"       => false,
            "lastInsertId"  => null
        ];
        
        try{
            $pdo = $this->connection->getConnection();

            $keys = $values = $keyParams = array();

            foreach ($data as $key => $value) {
                $keyParam           = ":{$key}";
                $keyParams[]        = $keyParam;
                $keys[]             = $key;
                $values[$keyParam]  = $value;
            }

            $sql = "INSERT INTO `{$table}` (" . implode(",", $keys) . ") VALUES (" . implode(",", $keyParams) . ")";

            $pdoStatement = $pdo->prepare($sql);
            
            $return["success"]      =  $pdoStatement->execute($values);
            $return["lastInsertId"] =  $pdo->lastInsertId();
        }catch(PDOException $e){
            $return["success"] = false;
        }
        
        return (object) $return;
    }
    
    public function delete($table, array $conditions){
        try{
            $pdo = $this->connection->getConnection();

            $values = $whereString = array();

            foreach ($conditions as $key => $value) {
                $keyParam           = ":{$key}";
                $whereString[]      = "{$key} = {$keyParam}";
                $values[$keyParam]  = $value;
            }

            $sql = "DELETE FROM `{$table}` WHERE " . implode(" AND ", $whereString);

            $pdoStatement = $pdo->prepare($sql);

            return $pdoStatement->execute($values);
        }catch(PDOException $e){
            return false;
        }
    }
    
    public function update($table, array $data, array $conditions){
        try{
            $pdo = $this->connection->getConnection();

            $setString = $whereString = array();

            foreach ($data as $key => $value) {
                $keyParam           = ":{$key}";
                $setString[]        = "{$key} = {$keyParam}";
                $values[$keyParam]  = $value;
            }

            foreach ($conditions as $key => $value) {
                $isRepeatedKey = array_key_exists($key, $data);
                
                $keyParam           = ($isRepeatedKey) ? ":repeated_{$key}" : ":{$key}";
                $whereString[]      = "{$key} = {$keyParam}";
                $values[$keyParam]  = $value;
            }

            $sql = "UPDATE `{$table}` SET " . implode(",", $setString) . " WHERE " . implode(" AND ", $whereString);
            
            $pdoStatement = $pdo->prepare($sql);

            return $pdoStatement->execute($values);
        }catch(PDOException $e){
            return false;
        }
    }
    
    public function execute($queryString){
        try{
            $pdo            = $this->connection->getConnection();
            $pdoStatement   = $pdo->prepare($queryString);
            
            return $pdoStatement->execute();
        }catch(PDOException $e){
            return false;
        }
    }
}