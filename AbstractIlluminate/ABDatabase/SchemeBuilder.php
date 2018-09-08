<?php

    /**
     * @author Okechukwu Somtochukwu
     * @date 2/08/2018
     * @project Abstract Framework
     */

    namespace AbstractIlluminate\ABDatabase;
    use \AbstractIlluminate\ABException\Error;
    /**
     * @class SchemeBuilder
     * @description This class handles the building of database schemes which includes creating * database, table, index, droping database and table
     */
     
    class SchemeBuilder
    {
        /**
         * @property $conn
         * @description this property holds the database connecction object of PDO class
         * @value object
         */
        private $conn = null; 


        /**
         * @method __construct()
         * @description - instantiates the connection class
         * @param {object} $conn - connection argument object of the connection class
         */
        public function __construct(){
            if(empty($this->conn)){
                
                Connection::configureDb( new Dbconfig('AbstractIlluminate/ABDatabase/config.json'));
                $this->conn = Connection::open();
                
            }
        }

       

    }