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
        public $sqlQuery = null;
        public $values = [];
        public $placeholder = [];
        private $conn = null;
        private $columns = null;
        private $condition = null;
        public $queryoptions = [];
        public $isSelect = false;
        public $tables = null;


        public function __construct(PDO $con = NULL){
            Connection::configureDb(
                new Dbconfig('AbstractIlluminate/ABDatabase/config.json')
            );
            Connection::open();
            $this->conn = Connection::$conn;

        }

        // public function getTables(array $queryoptions = null) {
        //     $this->queryoptions  = $queryoptions ?? $this->queryoptions;
        //     $tablesArray = array_keys($this->)
        // }

    /**
         * @method getPlaceholders()
         * @param {array} $queryoptions - this holds an associative array of the table columns matching to there values
         * @return array
         */
        private function getPlaceholders(array $queryoptions = null) :array
        {
            $workinArray = $queryoptions ?? $this->queryoptions;

            //make sure placeval did not contain array, if it contains array work it out
            array_walk($workinArray, function (&$item, &$key) use (&$workinArray){
                if(is_array($item)){
                    unset($workinArray[$key]);
                }
            });
            
            //Get the keys for placeholder
            $placekeys = array_map(function ($item){
                $item = explode("|", $item)[0];
                return trim(":".$item);
            }, array_keys($workinArray));
            //Gets the values for the placeholder
            $placeval = array_map(function (&$item) {
                return trim($item);
            },array_values($workinArray));
            
            //Check if array is empty
            if(empty($this->placeholder)){ //if empty fill in values into array
                $this->placeholder = array_combine($placekeys, $placeval);
                //Return new array 
                return $this->placeholder;
            } else{
                //if is not empty update values in array
                array_walk($workinArray, function($item, $key){
                    $key = explode("|", $key)[0];
                    $key = rtrim($key);
                    $this->placeholder[":".$key] = trim($item);
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
        private function getColumns() :string
        {
            $this->columns = array_reduce(array_keys($this->queryoptions), function ($carry, $item){
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
        public function selectInto(array $queryoptions, $tablename = null, $condition = null, string $columns = null) :object
        {
            //set $this->queryoption
            $this->queryoptions = $queryoptions;

            $this->placeholder = $this->getPlaceholders();
            $condition = is_null($condition)? null : $this->isBool($condition);
            
            $condition = $this->getConditions(null, $condition);
            
            $columns = $columns ?? "*";
            $this->sqlQuery = "SELECT {$columns} FROM {$tablename} WHERE {$condition}";
            $this->isSelect = true;
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
            $this->queryoptions = $queryoptions;
            
            //get the query placeholder
            $this->placeholder = $this->getPlaceholders();

            //get the columns to compare query with
            $this->columns = $this->getColumns();
            //Reduce the array key to be used as INSERT values
            $this->values = $this->getValues($this->placeholder);
            
            //create instert sql query
            $this->sqlQuery = "INSERT INTO {$tablename}({$this->columns}) VALUES ({$this->values})";

            return $this;

        }

        /**
         * @method getCondition()
         * @description - prepare the query condition with the appropriate columns, 
         * placeholders and conditions 
         * @return string
         */

        private function getConditions (array $queryoptions = null, string $condition = null) :string
        {
            $this->queryoptions  = $queryoptions ?? $this->queryoptions;
            //get keys
            $workinArray = $this->queryoptions;
            $keys = array_keys($workinArray);
            $preparedConditions = null;
            $removed = false;
            $build = array_reduce($keys, function ($carry, $item) use ($condition, &$preparedConditions, &$workinArray, &$keys, &$removed){    
                
                $currentValue = $workinArray[$item]; //get the current value of the queryoptions

                if(is_array($currentValue)){ // if current value is an array create a valid condition for it
                    //set column placeholder counter for any value that is an array to properly set the query
                    $counter = 1;
                    //get values from the first array item which also an array  
                    $innerValues = $currentValue[0];
                    
                    $queryConditions = isset($currentValue[1]) ? explode(',', $currentValue[1]): null;

                    $realInner = isset($queryConditions[1]) ? $queryConditions[0] : 'OR';
                    
                    // get the condition to be used for this values
                    $innerCondition = is_null($queryConditions)? 'OR' : $queryConditions[1]??  $this->isBool($queryConditions[0]) ;


                    $preparedConditions = array_reduce($innerValues, function ($newCarry, $newItem) use ($item, $innerCondition, &$counter, $realInner, &$workinArray ){
                        
                        $isExplode = explode("|", $newItem);
                        $innerCond = count($isExplode) == 2 ? $isExplode[0] : '=';
                        $innerColval = count($isExplode) == 2? $isExplode[1] : $isExplode[0];
                        
                        $innerPlaceholder = ":".$item.$counter;

                        $newCarry .= !$newCarry ?  " {$this->isBool($realInner)} {$item} {$innerCond}  $innerPlaceholder " : $this->isBool($innerCondition). " ".$item." {$innerCond} $innerPlaceholder ";

                        $this->placeholder[$innerPlaceholder] = trim($innerColval);
                        $counter++;
                       return $newCarry;
                    });
                    $carry .= !$carry ? " " . ltrim(trim($preparedConditions), $this->isBool($realInner) ) . " "  : $preparedConditions;
                    
                    return $carry;
                } elseif (!is_array($item)){
                    //get the condition to use for this column
                    $cond = explode("|", $item);
                    //get column name 
                    $colname = $cond[0];
                    $placeholder = $cond[1] ?? "=";
                    //build query
                    $carry .= !$carry ?  $colname . " {$placeholder}  :$colname " : $this->isBool($condition) ." ".$colname . " {$placeholder}  :$colname " ;
                    
                    return $carry;
                }
                    
                
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
            $condition = trim(strtoupper($condition)) ;
             if(($condition != "OR") && ($condition != "AND") && ($condition != ",")) {
                    throw new \Error('Only "OR" or "AND" are accepted for sql condition');
                    
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
            //set $this->queryoption
            $this->queryoptions = $queryoptions;
            //get the length of the columns
            $colLenght = count($this->queryoptions);

            //get the query placeholder
            $this->placeholder = $this->getPlaceholders();
            //return the prepared columns, their conditions and placeholders
            $condition = $this->getConditions(null, $condition);

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
        public function condition(string $condition, array $conditonOptions = null) :object
        {
            $this->placeholder = $this->getPlaceholders($conditonOptions);
            $condition = $this->isBool($condition);
            $condition = $condition ." ". $this->getConditions($conditonOptions, $condition);
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
            $this->conn->exec('use abstract');
            $stmt = $this->conn->prepare($this->sqlQuery);
            $stmt->execute($this->placeholder);

            if($this->isSelect){
                return $stmt->fetchAll();
            }

            }
     }
