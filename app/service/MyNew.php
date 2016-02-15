<?php

require_once __PATH__ . "/app/model/New.php";
require_once __PATH__ . "/lib/utils.php";

class Service_MyNew {
    //constants
    
    public $news;

    public function __construct() {
        $this->news = new Service_MyNew_News_Resource();
    }
}

//Resource SCRUD Management
class Service_MyNew_News_Resource {

    public function listNews(array $optionParams = null) {
        //[id, title, status, dateCreate] params
        $CONDITIONALS_PART = [];
        
        if($optionParams && is_array($optionParams)){
            foreach ($optionParams as $key => $value){
                switch ($key){
                    case "id":
                        if(is_array($value)){
                            $value = array_map(function($element){
                                return "'{$element}'";
                            }, $value);
                            
                            $value = implode(",", $value);
                            
                            $CONDITIONALS_PART[]= "{$key} IN ({$value})";
                        }else{
                            $CONDITIONALS_PART[]= "{$key} = '{$value}'";
                        }
                        break;
                }
            }
        }
        
        $CONDITIONALS_PART_STRING = ($CONDITIONALS_PART)? "WHERE " . implode(" AND ", $CONDITIONALS_PART) : null;
        
        $query = "
        SELECT id,
        title,
        description,
        date_create,
        status
        FROM news
        {$CONDITIONALS_PART_STRING}
        ";
        
        $newsData = db::fetchAll($query);
        
        $news = [];
        
        foreach ($newsData as $newData){
            $newData = array_merge((array) $newData, [
                "dateCreate" => $newData->date_create
            ]);
            
            $news[] = new _New($newData);
        }
        
        return $news;
    }
    
    public function insert(_New $new){
        $affectedRows = (bool) db::insert("news", [
          "title"       => $new->getTitle(),
          "description" => $new->getDescription(),
          "date_create" => $new->getDateCreate(),
          "status"      => $new->getStatus()
        ]);
        
        return $affectedRows;
    }
    
    public function update(_New $new){
        $affectedRows = (bool) db::update("news", [
          "title"       => $new->getTitle(),
          "description" => $new->getDescription(),
          "date_create" => $new->getDateCreate(),
          "status"      => $new->getStatus()
        ], [
          "id"          => $new->getId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($newId){
        $affectedRows = (bool) db::delete("news", [
            "id" => $newId
        ]);
        
        return $affectedRows;
    }
}
