<?php

namespace Toolkit\Util;

class File{
    protected $filename;
    
    final public function __construct($filename) {
        $this->filename = $filename;
    }
    
    public function hasExtension($extension){
        return strtolower(pathinfo($this->filename, PATHINFO_EXTENSION)) === strtolower($extension);
    }
    
    public function isImage(){
        $IMAGE_EXTENSIONS = ["jpg", "png", "gif"];
        $fileExtension = pathinfo($this->filename, PATHINFO_EXTENSION);
        
        return in_array($fileExtension, $IMAGE_EXTENSIONS);
    }

    public function isVideo(){
        $VIDEO_EXTENSIONS = [
            "3g2", "3gp", "aaf", "asf", "avchd", "avi", "drc", "flv", "m2v",
            "m4p", "m4v", "mkv", "mng", "mov", "mp2", "mp4", "mpe", "mpeg",
            "mpg", "mpv", "mxf", "nsv", "ogg", "ogv", "qt", "rm", "rmvb", "roq",
            "svi", "vob", "webm", "wmv", "yuv"
        ];

        $fileExtension = pathinfo($this->filename, PATHINFO_EXTENSION);
        
        return in_array($fileExtension, $VIDEO_EXTENSIONS);
    }
}