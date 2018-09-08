<?php

    /**
     * @author Okechukwu Somtochukwu
     * @date 2/08/2018
     * @project Abstract Framework
     */

    /**
     * @namespace AbstractIlluminate\ABDatabase
     * @description This namespace contains the every Database class Abstract Framework
     */
    namespace AbstractIlluminate\ABDatabase;
    use \AbstractIlluminate\ABException\Error;
    /**
     * @class Connection
     * @description, this class connects and disconnect the database
     */

    class Connection
    {
        /**
         * @property $config
         * @description, holds the configuration class of the connection
         */
        private static $config = null;

        
        /**
         * @property $conn
         * @description, holds the connection class of the class
         */
        public static $conn = false;

        /**
         * @property $isConnected
         * @description, holds boolean value if database is connected or not
         * @value
         */
        public static $isConnected = false;


        /**
         * @property $dsn
         * @description, holds the connection database dsn
         */
        private static $dsn = false;
        

        /**
         * @__construct 
         * @description, creates a private __construct to avoid the instatiation of this class
         */
        private function __construct(){}

        public static function configureDb(Dbconfig $config = null) {
            
            self::$config = $config;
            
            self::$dsn = $config->dvr.":host=".$config->hst.";charset=$config->chst"; //Holds the database connection first argument 
            
        }  

         

        /**
          * @method open()
          * @param null
          * @description, this method opens the connection to the database
          * @return boolean 
          */
        public static function open() :bool
        {
            if(!self::$isConnected){
                try{
                    self::$conn = new \PDO(self::$dsn, self::$config->usn, self::$config->pwd,
                self::$config->setOptions([
                    'error' => 'exception'
                ]));
                self::$isConnected = true;
                return true;
                } catch(\PDOException $e){
                    $error_no = (int)  str_replace('/[A-Z]/i', null, $e->getMessage());
                    throw new Error($e->getMessage(), $error_no, $e->getFile(), $e->getLine());
                }
            } else{
                throw new Error("Can't connect to Database, a connection is already opened", 0, $_SERVER['PHP_SELF']);
                return true;
            }
            

        }

        /**
          * @method close()
          * @param null
          * @description, this method close the connection to the database
          * @return this class 
          */
        public static function close() :bool
        {
            if (self::$conn) {
                self::$conn = null;
                self::$isConnected = false;
                return true;
            }
        }
    }

                