<?php

class app_configs{
    private static $namespaces = [
        'users',
        'news'
    ];
    
    public static function getNamespaces() {
        return self::$namespaces;
    }
}