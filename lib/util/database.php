<?php

use Toolkit\Database\Connection;
use Toolkit\Database\DatabaseUtil;

class db{
    public static function util(){
        $connectionsParameter = require __PATH__ . "/lib/connections.php";
        
        $connection = new Connection($connectionsParameter);
        
        return new DatabaseUtil($connection);
    }
    
    public static function getConnection(){
        $connectionsParameter = require __PATH__ . "/lib/connections.php";
        
        return new Connection($connectionsParameter);
    }
}