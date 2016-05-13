<?php

namespace Toolkit\Table;

use Toolkit\Model\Model;

class Table extends Model {

    protected $name;
    
    function getName() {
        return $this->name;
    }

    function setName($name) {
        $this->name = $name;
    }
    
}