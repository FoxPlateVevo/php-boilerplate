<?php

class app_configs{
    private static $namespaces = [
        'user',
        'new'
    ];
    
    public static function getNamespaces() {
        return self::$namespaces;
    }
}