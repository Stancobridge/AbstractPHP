<?php

    namespace AbstractIlluminate\ABDatabase;
    use \AbstractIlluminate\ABException\Error;
    /**
     * @class name database
     * @param {PDO} $con
     * @description --contains the database connection object
     */

     class Database
     {
        public $connect = null;
        public $sqlQuery = null;
        public $colLenght = 0;
        public $passedCol = 0;
        public $tablename = null;
        public $values = [];
        public $placeholder = [];
        private $prevUse = null;
        public $tablechecker = 0;
        public $defaultname = null;
        private $conn = null;
        private $columns = null;
        public function __construct(PDO $con = NULL){
            Connection::configureDb(
                new Dbconfig('AbstractIlluminate/ABDatabase/config.json')
            );
            Connection::open();
            $this->conn = Connection::$conn;

        }

    /**
         * @method getPlaceholders()
         * @param {array} $queryoptions - this holds an associative array of the table columns matching to there values
         * @return array
         */
        private function getPlaceholders(array $queryoptions) :array
        {
            //Get the keys for placeholder
            $placekeys = array_map(function ($item){
                return ":".$item;
            }, array_keys($queryoptions));

            //Gets the values for the placeholder
            $placeval = array_values($queryoptions);
            $this->placeholder = array_combine($placekeys, $placeval);
            return $this->placeholder;
            
        }

        /**
         * @method getColumns()
         * @param {array} $queryoptions - this holds an associative array of the table columns matching to there values
         * @return array
         */
        private function getColumns(array $queryoptions) :string
        {
            $this->columns = array_reduce(array_keys($queryoptions), function ($carry, $item){
                return !$carry ? $item : ("$carry, $item");
            });

            return $this->columns;
        }

        /**
         * @method getColumns()
         * @param {array} $queryoptions - this holds an associative array of the table columns matching to there values
         * @return array
         */
        private function getValues(array $placeholder) :string
        {
            $this->values = array_reduce(array_keys($this->placeholder), function ($carry, $item){
                return !$carry ? $item : ("$carry, $item");
            });
            return $this->values;
        }


    /**
     * @method selectInto
     * @param {string, array} - $tablename, refers to the table you are querying - queryoptions, specifies the query options
     * @description this method builds a query from the specified table with the given query options
     * @return bool
     */
        public function selectInto(array $queryoptions, string $use = null, $tablename = null) :object
        {
            
            
            $values = null;
            $use = $use;
            $this->colLenght = count($queryoptions);
            if (empty($this->tablename) && !empty($tablename)){
                $this->tablename = $tablename;
                $this->tablechecker = 1;
                $this->defaultname =$this->tablename;
            } elseif (!empty($this->tablename && $tablename)){
                throw new Error('TABLE NAME ALREADY SET AND CAN ONLY BE SELECTED ONCE!');

            }
             if ($this->tablechecker==1){
                if (empty($this->prevUse)){
                    $this->prevUse = $use;
                    array_walk($queryoptions, function ($val, $col) use (&$values, &$use){
                        $this->passedCol++;
                        if (!is_array($col) && !empty($use) && $this->passedCol < $this->colLenght  ){
            
                            $this->placeholder[":".$col] = $val;
                            $this->sqlQuery .= "SELECT FROM {$this->defaultname} WHERE " .$col." = ". ":".$col . " ${use} ";                    
                        
                        }else {
                            $this->placeholder[":".$col] = $val;
                            $this->sqlQuery .= $col." = ". ":".$col;
                        }
                        
                    });
                }
                else {
                    $this->prevUse = $use;
                    array_walk($queryoptions, function ($val, $col) use (&$values, &$use){
                        $this->passedCol++;
                        if (!is_array($col) && !empty($use) && $this->passedCol < $this->colLenght  ){
            
                            $this->placeholder[":".$col] = $val;
                            $this->sqlQuery .= " ".$this->prevUse ." " .  $col." = ". ":".$col . " ${use} ";                    
                        
                        }else {
                            $this->placeholder[":".$col] = $val;
                            $this->sqlQuery .= $col." = ". ":".$col;
                        }
                        
                    });
                }
             }else {
                throw new Error('A VALIID NAME IS REQUIRED FOR THE TABLE');

             }
        
            $queryoptions = $queryoptions;
            $this->passedCol = 0;
            $use= null;
            return $this;
        }
       

        /**
         * @method insertInto()
         * @param {array, string} $queryoptions - this an associative array of each column 
         * mapping to their respective value they are queried against, $tablename - this 
         * property, $tablename this holds the table to be inserted into
         *  holds the name of the table to query from
         * @description - this method performs an insert query into the provided $tablename  
         * @return object
         */
        public function insertInto(array $queryoptions, string $tablename) :object
        {
            //get the query placeholder
            $this->placeholder = $this->getPlaceholders($queryoptions);

            //get the columns to compare query with
            $this->columns = $this->getColumns($queryoptions);

            //Reduce the array key to be used as INSERT values
            $this->values = $this->getValues($this->placeholder);

            //create instert sql query
            $this->sqlQuery = "INSERT INTO {$tablename}({$this->columns}) VALUES ({$this->values})";

            return $this;

        }

        /**
         * @method deleteRecord()
         * @param {array, string} $queryoptions - this an associative array of each column 
         * mapping to their, respective value they are queried against, $tablename - this 
         * property, $tablename this holds the table to be deleted from
         *  holds the name of the table to query from
         * @description - this method creates a delete query for the provided $tablename  
         * @return object
         */
        public function deleteRecord(array $queryoptions, string $tablename, string $condition = null) :object
        {
            //get the length of the columns
            $colLenght = count($queryoptions);

            //get the query placeholder
            $this->placeholder = $this->getPlaceholders($queryoptions);

            //get the query values
            $this->values = $this->getValues($this->placeholder);

            // //get the query columns
            $this->columns = $this->getColumns($queryoptions);  

            if($colLenght == 1){ //If one column is being selected

                //create delete sql query
                $this->sqlQuery = "DELETE FROM {$tablename} WHERE {$this->columns} = key($this->values) ";
                
                return $this;
            } elseif($colLenght > 1 && !empty($queryoptions)){
                //get array of columns
                $keys = array_keys($queryoptions);

                //creates the where clause of this delete
                $where = array_reduce($keys, function ($carry, $item) use ($condition) {
                    return !$carry ? "{$item} = :{$item} " : $carry ." {$condition} {$item} = :{$item}";
                });

            $this->sqlQuery = "DELETE FROM {$tablename} WHERE {$where}";
            return $this;
            }
        }

        // public function or(array $queryoptions) :object
        // {

        // }
     }
