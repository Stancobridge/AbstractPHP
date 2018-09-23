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
        private $condition = null;
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
                $item = explode("|", $item)[0];
                return rtrim(":".$item);
            }, array_keys($queryoptions));
            //Gets the values for the placeholder
            $placeval = array_values($queryoptions);

            //Checkb if array is empty
            if(empty($this->placeholder)){ //if empty fill in values into array
                $this->placeholder = array_combine($placekeys, $placeval);
                //Return new array
                return $this->placeholder;
            } else{
                //if is not empty update values in array
                array_walk($queryoptions, function($item, $key){
                    $key = explode("|", $key)[0];
                    $key = rtrim($key);
                    $this->placeholder[":".$key] = $item;
                });

                //Return updated array
                return $this->placeholder;
            }
            
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
            if(empty($this->placeholder)) return 'empty';
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
        public function selectInto(array $queryoptions, $tablename = null, $condition = null) :object
        {
            $this->placeholder = $this->getPlaceholders($queryoptions);
            $condition = is_null($condition)? null : $this->isBool($condition);
            $condition = $this->getConditions($queryoptions, $condition);
            $this->sqlQuery = "SELECT FROM {$tablename} WHERE {$condition}";
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
         * @method getCondition()
         * @param {array} $queryoptions - holds array
         * @description - checks if $condition string is "AND" or "OR"   
         * @return string
         */

        private function getConditions (array $queryoptions, string $condition = null) :string
        {
            //get keys
            $keys = array_keys($queryoptions);

            $build = array_reduce($keys, function ($carry, $item) use ($condition){    
                //get the condition to use for this column
                $cond = explode("|", $item);
                //get column name 
                $colname = $cond[0]?? "=";
                $placeholder = $cond[1] ?? "=";
                //build query
                $carry .= !$carry ?  $colname . " {$placeholder}  :$colname " : $this->isBool($condition) ." ".$colname . " {$placeholder}  :$colname " ;
                return $carry;
                
            });
            return rtrim($build, $condition);
        } 

         /**
         * @method isBool()
         * @param {string} $condition - holds the string to check
         * @description - checks if $condition string is "AND" or "OR"   
         * @return string
         */
        public function isBool($condition) :string{
            $condition = strtoupper($condition) ;
             if(($condition != "OR") && ($condition != "AND") && ($condition != ",")) {
                    throw new \Error('Only "OR" and "AND" is accepted in the third argument');
                    
                } else{
                    $condition =  $condition ;
                    return $condition;
                }
        }
        /**
         * @method deleteRecord()
         * @param {array, string} $queryoptions - this an associative array of each column 
         * mapping to their, respective value they are queried against, $tablename - this 
         * property, $tablename this holds the table to be deleted from
         * @description - this method creates a delete query for the provided $tablename  
         * @return object
         */
        public function deleteRecord(array $queryoptions, string $tablename, string $condition = null) :object
        {
            //get the length of the columns
            $colLenght = count($queryoptions);

            //get the query placeholder
            $this->placeholder = $this->getPlaceholders($queryoptions);
            //return the prepared columns, their conditions and placeholders
            $condition = $this->getConditions($queryoptions, $condition);

            $this->sqlQuery = "DELETE FROM {$tablename} WHERE $condition ";
                
            return $this;
            
        }

        /**
         * @method condition()
         * @param {array, string} $queryoptions - this an associative array of each column 
         * mapping to their, respective value they are queried against, $condition - this 
         * property holds the condition is OR or AND is to be used
         * @description - this method creates a condition query for the provided $tablename  
         * @return object
         */
        public function condition(array $queryoptions, string $condition) :object
        {
            $this->placeholder = $this->getPlaceholders($queryoptions);
            $condition = $this->isBool($condition);
            $condition = $condition ." ". $this->getConditions($queryoptions, $condition);
            $this->sqlQuery .= " {$condition}";

            return $this;
        }

        /**
         * @method name orderBy()
         * @param {array, string} $columnNames $ASC/DeSC -$columnNames requires a NON associative array
         * @description this method takes an array of column names and a string of either ASC/DSC to order the query 
         */
        public function orderBy(array $columnNames, string $order = "ASC"): object {
            array_reduce($columnNames,function ($prevCol,$currentCol) use (&$orderCol, &$orderQuery, &$order){
                $orderCol .= $currentCol . ",";
                $substr = substr($orderCol,0,strlen($orderCol)-1);
                $orderQuery = " ORDER BY $substr {$order} ";
                
            });
            $this->sqlQuery .= $orderQuery; 
            return $this;
        }

        /**
         * @method updateRecord()
         * @param {array, string} $columnNames $ASC/DeSC -$columnNames requires a NON associative array
         * @description this method takes an array of column names and a string of either ASC/DSC to order the query 
         */

         public function updateRecord(array $set, array $where, string $tablename, $condition = null ) :object{
            $this->placeholder = $this->getPlaceholders($set);
            $this->placeholder = $this->getPlaceholders($where);
            $setCondition = $this->getConditions($set, ',');
            $whereConditon = $this->getConditions($where, $condition);
            $this->sqlQuery = "UPDATE {$tablename} SET {$setCondition} WHERE  {$whereConditon}";
            return $this;
         }

         public function commit(){
            $this->conn->exec('use myusers');
            $stmt = $this->conn->prepare($this->sqlQuery);
            $stmt->execute($this->placeholder);

            }
     }
