<?php

require_once __PATH__ . "/app/model/Model.php";

class _New extends Model {
    //constants
    const STATUS_PUBLIC     = "public";
    const STATUS_PRIVATE    = "private";
    
    protected $id;
    protected $title;
    protected $description;
    protected $dateCreate;
    protected $status;
    
    function getId() {
        return $this->id;
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

    function setId($id) {
        $this->id = $id;
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
