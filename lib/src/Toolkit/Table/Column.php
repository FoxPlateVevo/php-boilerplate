<?php

namespace Toolkit\Table;

use Toolkit\Model\Model;

class Column extends Model {
    // Constants
    // String types
    const DATA_TYPE_CHAR        = "char";
    const DATA_TYPE_VARCHAR     = "varchar";
    const DATA_TYPE_TEXT        = "text";
    // Time types
    const DATA_TYPE_DATE        = "date";
    const DATA_TYPE_DATETIME    = "datetime";
    const DATA_TYPE_TIMESTAMP   = "timestamp";
    const DATA_TYPE_TIME        = "time";
    // Numeric types
    const DATA_TYPE_INT         = "int";
    const DATA_TYPE_DECIMAL     = "decimal";
    
    protected $characterMaximumLength;
    protected $characterOctectLength;
    protected $columnKey;
    protected $columnName;
    protected $columnType;
    protected $columnComment;
    protected $dataType;
    protected $datetimePrecision;
    protected $extra;
    protected $isNullable;
    protected $numericPrecision;
    protected $numericScale;
    protected $ordinalPosition;
    
    function getCharacterMaximumLength() {
        return $this->characterMaximumLength;
    }

    function getCharacterOctectLength() {
        return $this->characterOctectLength;
    }

    function getColumnKey() {
        return $this->columnKey;
    }

    function getColumnName() {
        return $this->columnName;
    }

    function getColumnType() {
        return $this->columnType;
    }

    function getColumnComment() {
        return $this->columnComment;
    }

    function getDataType() {
        return $this->dataType;
    }

    function getDatetimePrecision() {
        return $this->datetimePrecision;
    }

    function getExtra() {
        return $this->extra;
    }

    function getIsNullable() {
        return $this->isNullable;
    }

    function getNumericPrecision() {
        return $this->numericPrecision;
    }

    function getNumericScale() {
        return $this->numericScale;
    }

    function getOrdinalPosition() {
        return $this->ordinalPosition;
    }

    function setCharacterMaximumLength($characterMaximumLength) {
        $this->characterMaximumLength = $characterMaximumLength;
    }

    function setCharacterOctectLength($characterOctectLength) {
        $this->characterOctectLength = $characterOctectLength;
    }

    function setColumnKey($columnKey) {
        $this->columnKey = $columnKey;
    }

    function setColumnName($columnName) {
        $this->columnName = $columnName;
    }

    function setColumnType($columnType) {
        $this->columnType = $columnType;
    }

    function setColumnComment($columnComment) {
        $this->columnComment = $columnComment;
    }

    function setDataType($dataType) {
        $this->dataType = $dataType;
    }

    function setDatetimePrecision($datetimePrecision) {
        $this->datetimePrecision = $datetimePrecision;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }

    function setIsNullable($isNullable) {
        $this->isNullable = $isNullable;
    }

    function setNumericPrecision($numericPrecision) {
        $this->numericPrecision = $numericPrecision;
    }

    function setNumericScale($numericScale) {
        $this->numericScale = $numericScale;
    }

    function setOrdinalPosition($ordinalPosition) {
        $this->ordinalPosition = $ordinalPosition;
    }

    // Methods
    
    public function isAutoIncrement(){
        return (bool) preg_match('/auto_increment/i', $this->getExtra());
    }
    
    public function isPrimaryKey(){
        return (bool) preg_match('/pri/i', $this->getColumnKey());
    }
}