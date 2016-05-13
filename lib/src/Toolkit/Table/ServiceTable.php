<?php

namespace Toolkit\Table;

use Toolkit\Table\Table;
use Toolkit\Database\DatabaseUtil;
use Toolkit\Database\Connection;

class ServiceTable {
    private $connection;
    
    public $tables;
    public $columns;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
        
        $this->tables   = new ServiceTable_Table_Resource($this->connection);
        $this->columns  = new ServiceTable_Column_Resource($this->connection);
    }
}

//Resource SCRUD Management
class ServiceTable_Table_Resource {
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function listTables() {
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        SELECT TABLE_NAME
        FROM information_schema.tables
        WHERE TABLE_SCHEMA = '{$this->connection->getDbName()}'
        AND TABLE_TYPE = 'BASE TABLE';
        ";
        
        $arrayObjects = $databaseUtil->fetchAll($query);
        
        $results = [];
        
        foreach ($arrayObjects as $object){
            $results[] = new Table([
                "name"  => $object->TABLE_NAME
            ]);
        }
        
        return empty($results) ? null : $results;
    }
    
    public function insert(Table $object){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        CREATE TABLE `{$this->connection->getDbName()}`.`{$object->getName()}`
        (
            default_id INTEGER AUTO_INCREMENT,
            PRIMARY KEY (default_id)
        );    
        ";
        
        return $databaseUtil->execute($query);
    }
    
    public function get($tableName){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        SELECT TABLE_NAME
        FROM information_schema.tables
        WHERE TABLE_SCHEMA = '{$this->connection->getDbName()}'
        AND TABLE_NAME = '{$tableName}'
        AND TABLE_TYPE = 'BASE TABLE';
        ";
        
        $object =  $databaseUtil->fetchOne($query);
        
        return new Table([
            "name"  => $object->TABLE_NAME
        ]);
    }
    
    public function update(Table $object, $newTableName = null){
        if($newTableName){
            $databaseUtil = new DatabaseUtil($this->connection);
        
            $query = "
            RENAME TABLE `{$object->getName()}` TO `{$newTableName}`;
            ";
            
            return $databaseUtil->execute($query);
        }else{
            return false;
        }
    }
    
    public function delete($tableName){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        DROP TABLE `{$this->connection->getDbName()}`.`{$tableName}`;
        ";
        
        return $databaseUtil->execute($query);
    }
}

class ServiceTable_Column_Resource {
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function listColumns(Table $table) {
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        SELECT 
        CHARACTER_MAXIMUM_LENGTH,
        CHARACTER_OCTET_LENGTH,
        COLUMN_KEY,
        COLUMN_NAME,
        COLUMN_TYPE,
        COLUMN_COMMENT,
        DATA_TYPE,
        DATETIME_PRECISION,
        EXTRA,
        IS_NULLABLE,
        NUMERIC_PRECISION,
        NUMERIC_SCALE,
        ORDINAL_POSITION
        FROM information_schema.columns
        WHERE TABLE_NAME = '{$table->getName()}'
        AND TABLE_SCHEMA = '{$this->connection->getDbName()}';
        ";
        
        $arrayObjects = $databaseUtil->fetchAll($query);
        
        $results = [];
        
        foreach ($arrayObjects as $object){
            $results[] = new Column([
                "characterMaximumLength"    => $object->CHARACTER_MAXIMUM_LENGTH,
                "characterOctectLength"     => $object->CHARACTER_OCTET_LENGTH,
                "columnKey"                 => $object->COLUMN_KEY,
                "columnName"                => $object->COLUMN_NAME,
                "columnType"                => $object->COLUMN_TYPE,
                "columnComment"             => $object->COLUMN_COMMENT,
                "dataType"                  => $object->DATA_TYPE,
                "datetimePrecision"         => $object->DATETIME_PRECISION,
                "extra"                     => $object->EXTRA,
                "isNullable"                => $object->IS_NULLABLE,
                "numericPrecision"          => $object->NUMERIC_PRECISION,
                "numericScale"              => $object->NUMERIC_SCALE,
                "ordinalPosition"           => $object->ORDINAL_POSITION
            ]);
        }
        
        return empty($results) ? null : $results;
    }
    
    public function insert(Table $table, array $dataToInsert){
        $success = false;
        
        switch ($dataToInsert["dataType"]){
            case Column::DATA_TYPE_CHAR:
                $success = $this->insertChar($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_VARCHAR:
                $success = $this->insertVarchar($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_TEXT:
                $success = $this->insertText($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_DATE:
                $success = $this->insertDate($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_DATETIME:
                $success = $this->insertDatetime($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_TIMESTAMP:
                $success = $this->insertTimestamp($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_TIME:
                $success = $this->insertTime($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_INT:
                $success = $this->insertInt($table, $dataToInsert);
                break;
            case Column::DATA_TYPE_DECIMAL:
                $success = $this->insertDecimal($table, $dataToInsert);
                break;
        }
        
        return $success;
    }
    
    public function get(Table $table, $columnName){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        SELECT 
        CHARACTER_MAXIMUM_LENGTH,
        CHARACTER_OCTET_LENGTH,
        COLUMN_KEY,
        COLUMN_NAME,
        COLUMN_TYPE,
        COLUMN_COMMENT,
        DATA_TYPE,
        DATETIME_PRECISION,
        EXTRA,
        IS_NULLABLE,
        NUMERIC_PRECISION,
        NUMERIC_SCALE,
        ORDINAL_POSITION
        FROM information_schema.columns
        WHERE COLUMN_NAME = '{$columnName}'
        AND TABLE_NAME = '{$table->getName()}'
        AND TABLE_SCHEMA = '{$this->connection->getDbName()}';
        ";
        
        $object =  $databaseUtil->fetchOne($query);
        
        return new Column([
            "characterMaximumLength"    => $object->CHARACTER_MAXIMUM_LENGTH,
            "characterOctectLength"     => $object->CHARACTER_OCTET_LENGTH,
            "columnKey"                 => $object->COLUMN_KEY,
            "columnName"                => $object->COLUMN_NAME,
            "columnType"                => $object->COLUMN_TYPE,
            "columnComment"             => $object->COLUMN_COMMENT,
            "dataType"                  => $object->DATA_TYPE,
            "datetimePrecision"         => $object->DATETIME_PRECISION,
            "extra"                     => $object->EXTRA,
            "isNullable"                => $object->IS_NULLABLE,
            "numericPrecision"          => $object->NUMERIC_PRECISION,
            "numericScale"              => $object->NUMERIC_SCALE,
            "ordinalPosition"           => $object->ORDINAL_POSITION
        ]);
    }
    
    public function update(Table $table, Column $column, array $dataToUpdate){
        $success = false;
        
        switch ($dataToUpdate["dataType"]){
            case Column::DATA_TYPE_CHAR:
                $success = $this->updateToChar($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_VARCHAR:
                $success = $this->updateToVarchar($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_TEXT:
                $success = $this->updateToText($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_DATE:
                $success = $this->updateToDate($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_DATETIME:
                $success = $this->updateToDatetime($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_TIMESTAMP:
                $success = $this->updateToTimestamp($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_TIME:
                $success = $this->updateToTime($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_INT:
                $success = $this->updateToInt($table, $column, $dataToUpdate);
                break;
            case Column::DATA_TYPE_DECIMAL:
                $success = $this->updateToDecimal($table, $column, $dataToUpdate);
                break;
        }
        
        return $success;
    }
    
    public function delete(Table $table, $columnName){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        DROP COLUMN `{$columnName}`
        ";
        
        return $databaseUtil->execute($query);
    }
    
    // Private methods
    
    private function insertChar(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        CHAR({$dataToInsert["characterMaximumLength"]}) NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertVarchar(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        VARCHAR({$dataToInsert["characterMaximumLength"]}) NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertText(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        TEXT NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertDate(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        DATE NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertDatetime(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        DATETIME NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertTimestamp(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        TIMESTAMP NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertTime(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        TIME NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertInt(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        INT NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function insertDecimal(Table $table, array $dataToInsert){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $finalNumericScale = (int) $dataToInsert["NumericScale"];
        
        $query = "
        ALTER TABLE `{$table->getName()}`
        ADD COLUMN `{$dataToInsert["columnName"]}` 
        DECIMAL({$dataToInsert["NumericPrecision"]}, {$finalNumericScale}) NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToChar(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        CHAR({$dataToUpdate["characterMaximumLength"]}) NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToVarchar(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        VARCHAR({$dataToUpdate["characterMaximumLength"]}) NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToText(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        TEXT NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToDate(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        DATE NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToDatetime(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        DATETIME NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToTimestamp(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        TIMESTAMP NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToTime(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        TIME NULL
        ";

        return $databaseUtil->execute($query);
    }
    
    private function updateToInt(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $REST_PART_STRING = "NULL";
        
        if($column->isPrimaryKey() && $dataToUpdate["autoIncrement"]){
            $REST_PART_STRING = "NOT NULL AUTO_INCREMENT";
        }
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        INT {$REST_PART_STRING} 
        ";
        
        return $databaseUtil->execute($query);
    }
    
    private function updateToDecimal(Table $table, Column $column, array $dataToUpdate){
        $databaseUtil = new DatabaseUtil($this->connection);
        
        $finalNumericScale = (int) $dataToUpdate["NumericScale"];
        
        $query = "
        ALTER TABLE `{$table->getName()}` 
        CHANGE `{$column->getColumnName()}` `{$dataToUpdate["columnName"]}` 
        DECIMAL({$dataToUpdate["NumericPrecision"]}, {$finalNumericScale}) NULL
        ";
        
        return $databaseUtil->execute($query);
    }
}
