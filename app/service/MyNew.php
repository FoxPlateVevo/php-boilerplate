<?php

class Service_MyNew {
    //constants
    
    public $news;

    public function __construct() {
        $this->news = new Service_MyNew_News_Resource();
    }
}

// Resource SCRUD Management
class Service_MyNew_News_Resource {

    public function listNews(array $optionParams = null) {
        $alternateAttributes = [
            "newId"         => "new_id",
            "status"        => "status"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT new_id,
        title,
        description,
        date_create,
        status
        FROM `new`
        {$CONDITIONALS_PART_STRING}
        ";
        
        $arrayObjects = db::util()->fetchAll($query);
        
        $results = [];
        
        foreach ($arrayObjects as $object){
            $results[] = new _New([
                "newId"         => $object->new_id,
                "title"         => $object->title,
                "description"   => $object->description,
                "dateCreate"    => $object->date_create,
                "status"        => $object->status
            ]);
        }
        
        return $results;
    }
    
    public function insert(_New $object){
        $insertedData =  db::util()->insert("new", [
            "title"       => $object->getTitle(),
            "description" => $object->getDescription(),
            "date_create" => $object->getDateCreate(),
            "status"      => $object->getStatus()
        ]);
        
        return $insertedData->success;
    }
    
    public function get($id){
        return array_pop($this->listNews([
            "newId" => $id
        ]));
    }
    
    public function update(_New $object){
        return db::util()->update("new", [
            "title"       => $object->getTitle(),
            "description" => $object->getDescription(),
            "date_create" => $object->getDateCreate(),
            "status"      => $object->getStatus()
        ], [
            "new_id"      => $object->getNewId()
        ]);
    }
    
    public function delete($id){
        return db::util()->delete("new", [
            "new_id"    => $id
        ]);
    }
}
