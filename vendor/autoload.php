<?php
    //Creates a default autoload handler function 
    //for all class, interface and namespaces
    spl_autoload_register(function ($classname){
        //Replace any \ or / with OS Directory Separator
        $classfile = preg_replace('/[\/ \\\]/', DIRECTORY_SEPARATOR, $classname) . ".php";

        //Check if class exists and it is readable before including
        if(file_exists($classfile) && is_readable($classfile)){
            
            require $classfile;

        } else{
            throw new \Error($classfile.' Class not found');
        }
    });
    