<?php

    /**
     * @author Okechukwu Somtochukwu
     * @date 1/08/2018
     * @project Abstract Framework
     */

    /**
     * @namespace AbstractIlluminate\ABDatabase
     * @description This namespace contains the every Database class Abstract Framework
     */
    namespace AbstractIlluminate\ABDatabase;
    
    /**
     * @class DBCONFIG
     * @description This class contains every configurations of the Database which consist of
     * database password, username, server, driver and also sets the attributes of the db driver
     */

     final class Dbconfig
     {
         public $usn = "";
         public $dbn = "";
         public $pwd = "";
         public $dvr = "";
         public $hst = "";

        /**
         * @property $attributes;
         * @description, This property holds the PDO Attributes values and the their
         * corresponding placeholder 
         */

         private $attributes = [
            'error' => [
                'exception' => \PDO::ERRMODE_EXCEPTION,
                'warning' => \PDO::ERRMODE_WARNING,
                'silent' => \PDO::ERRMODE_SILENT
                
            ],

            'fetch' => [
                'assoc' => \PDO::FETCH_ASSOC,
                'object' => \PDO::FETCH_OBJ,
                'both' => \PDO::FETCH_BOTH,
                'bound' => \PDO::FETCH_BOUND,
                'class' => \PDO::FETCH_CLASS,
                'fetch_into' => \PDO::FETCH_INTO,
                'fetch_lazy' => \PDO::FETCH_LAZY,
                'fetch_named' => \PDO::FETCH_NAMED,
                'fetch_num' => \PDO::FETCH_NUM,
                'fetch_props_late' => \PDO::FETCH_PROPS_LATE,
            ],

            'case' => [
                'lower' => \PDO::CASE_LOWER,
                'natural' => \PDO::CASE_NATURAL,
                'upper' => \PDO::CASE_UPPER
            ],

            'convert_null' => [
                'natural' => \PDO::NULL_NATURAL,
                'empty' => \PDO::NULL_EMPTY_STRING,
                'to_string' => \PDO::NULL_TO_STRING
            ]
         ];

         public $settedAttributes = [];

         private $errorOptions = [];

        /**
         * @method construct();
         * @param {string} $jsn - the resource url of the json configuration file
         * @decrisption, this method set the database connection requirements of pdo __construct
         * and assign it to this class corresponding properties
         * @return void
         */
         public function __construct($jsn) {
             $config = file_get_contents($jsn);
             $jsn = json_decode($config, true);

             if(\is_array($jsn)){
                 $this->hst = $jsn['db']['hst'];
                 $this->prt = $jsn['db']['prt'];
                 $this->dbname = $jsn['db']['dbname'];
                 $this->usn = $jsn['db']['usn'];
                 $this->pwd = $jsn['db']['pwd'];
                 $this->chst = $jsn['db']['chst'];
                 $this->dvr = $jsn['db']['dvr'];
                 
             }

         }
        
         /**
          * @method setOptions()
          * @param {array} $options - contains the associative array of the options to set 
          * and there values
          * @description, this method sets the option the PDO attributes, returning the 
          * required options to set in the instance of PDO
          * @return array
          */
         final public function setOptions(array $options = []) :array{
            $attribute = null;
            foreach ($options as $option => $value) {
                
                if(\key_exists($option, $this->attributes) && \key_exists($value, $this->attributes[$option])){
                    switch ($option){
                        case 'error' :
                            $attribute = \PDO::ATTR_ERRMODE;
                            break;
                        case 'fetch' :
                            $attribute = \PDO::ATTR_DEFAULT_FETCH_MODE;
                            break;
                        case 'case' :
                            $attribute = \PDO::ATTR_CASE;
                            break;
                        case 'convert_null':
                            $attribute = \PDO::ATTR_ORACLE_NULLS;
                            break;
                    }

                    $this->settedAttributes["{$attribute}"] = "{$this->attributes[$option][$value]}";
                    
                } else{
                    $this->errorOptions[] = $option;
                }
        
         
        
         }
         return $this->settedAttributes;
        }

         /**
          * @method getErrors()
          * @description, return the list of the attributes that was not included in  
          *  $this->attributes but was declared 
          * @return array
          */
          protected function getErrors() :array{
             return $this->errorOptions;

            
         }

         

     }