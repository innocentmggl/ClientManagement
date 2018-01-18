<?php

(defined('BLACKBOARD'))  or die('Access Denied. You are attempting to access a restricted file directly.');

class Autoloader
{
    public static function loader($class)
    {
        // array to hold classes
        $sources = array("app/class/controller/{$class}.php", "app/class/model/{$class}.php ",  "app/class/view/{$class}.php ", "app/class/helper/{$class}.php ", "app/conf/{$class}.php " );
        
        //check if file exists
        foreach ($sources as $source) {
            
            if (file_exists($source)) {
                require_once $source;
            }
        }         
    }
}