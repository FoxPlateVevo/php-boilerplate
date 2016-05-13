<?php

use Toolkit\Model\Model;

class _New extends Model {
    //constants
    const STATUS_PUBLIC     = "public";
    const STATUS_PRIVATE    = "private";
    
    protected $newId;
    protected $title;
    protected $description;
    protected $dateCreate;
    protected $status;
    
    function getNewId() {
        return $this->newId;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getDateCreate() {
        return $this->dateCreate;
    }

    function getStatus() {
        return $this->status;
    }

    function setNewId($newId) {
        $this->newId = $newId;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setDateCreate($dateCreate) {
        $this->dateCreate = $dateCreate;
    }

    function setStatus($status) {
        $this->status = $status;
    }

}
