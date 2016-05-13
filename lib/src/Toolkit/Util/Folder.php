<?php

namespace Toolkit\Util;

class Folder{
    protected $filename;

    final public function __construct($filename) {
        //valide if filename is dir
        if($filename && is_dir($filename)){
            $this->filename = $filename;
        }else{
            throw new Exception('$filename parameter isn\'t a directory');
        }
    }
    
    public function delete(){
        $components = $this->getComponents();
        
        foreach ($components as $component) {
            if(is_dir("{$this->filename}/{$component}")){
                $this->delete("{$this->filename}/{$component}");
            }else{
                unlink("{$this->filename}/{$component}"); 
            } 
        }
        
        rmdir($this->filename);
    }
    
    public function getComponents(){
        return array_diff(scandir($this->filename), [".", ".."]);
    }
    
    public static function create($filename){
        if(mkdir($filename, 0777, true)){
            return new Folder($filename);
        }else{
            return null;
        }
    }
}

