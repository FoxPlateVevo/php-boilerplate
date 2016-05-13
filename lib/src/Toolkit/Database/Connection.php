<?php

namespace Toolkit\Database;

use PDO;
use PDOException;
use Toolkit\Model\Model;

class Connection extends Model {
    protected $dbServer;
    protected $dbName;
    protected $dbUser;
    protected $dbPassword;
    
    function getDbServer() {
        return $this->dbServer;
    }

    function getDbName() {
        return $this->dbName;
    }

    function getDbUser() {
        return $this->dbUser;
    }

    function getDbPassword() {
        return $this->dbPassword;
    }

    function setDbServer($dbServer) {
        $this->dbServer = $dbServer;
    }

    function setDbName($dbName) {
        $this->dbName = $dbName;
    }

    function setDbUser($dbUser) {
        $this->dbUser = $dbUser;
    }

    function setDbPassword($dbPassword) {
        $this->dbPassword = $dbPassword;
    }

    // Methods
    
    public function getConnection(){
        $pdo = new PDO("mysql:host={$this->dbServer};dbname={$this->dbName}", $this->dbUser, $this->dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}