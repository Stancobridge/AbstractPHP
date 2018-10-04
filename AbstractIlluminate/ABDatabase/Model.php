<?php
    namespace AbstractIlluminate\ABDatabase;
    /**
     * @author Okechukwu Somtochukwu
     * @date 2/08/2018
     * @project Abstract Framework
     */

    /**
     * @namespace AbstractIlluminate\ABDatabase
     * @description This namespace contains the every Database class Abstract Framework
     */

     /**
      * @class Model
      */

      class Model extends Database implements ModelInterface
      {
        public $tablename = null;
        public $checklist = [];
        public $isValid = [];
        public $queryoptions = null;
        public $errorTable = [];
        public $errorMatch = [];
        public $errorMessage = [];

        /**
         * @property $regex 
         * @description - this property holds the validation regular expressions 
         * */
        public $regex = [
            'number' => '/^[0-9.]+$/',
            'int' => '/^[0-9]+$/',
            'alphanumeric' => '/^[A-Z0-9a-z]+$/',
            'text' => '/[a-z0-9 .!@#$%^&&*\(\)_\+ A-Z]/',
            'not_null' => "/(.|\s)*\S(.|'\s)*/"
        ];

        /**
         * @property $queryErrors 
         * @description - this property holds arrays of invalid data against the columns it is passed to 
         * */
        public $queryErrors = [];


        /**
         * @method processValidation()
         * @description - this method processes the validation of user input by
         * using what the user passed in the input which assigned as value of the
         * corresponding key in queryoptions, checking if their
         *  value matches the value of input to compare the value of the 
         * mapped regular expression for that input in $this->checklist array,
         * it then populates $this->queryErrors with either true or false wherever
         * the value of the input passes the matched regular expression for that 
         * column
         *  
         */

        public function processValidation($value, $column)
        {
            //Flip the regex array
            $flipregex = array_flip($this->regex);
            $explode = explode("|", $value);
            if(in_array($value, $flipregex)){
    
               $condition = $this->regex[$value];
               
               $matchRegex = preg_match($condition, $this->queryoptions[$column]);
               if($matchRegex){
                    $this->queryErrors[$column][] = true;
               } else {
                   $this->errorMatch[$column] = $condition;
                    $this->queryErrors[$column][] = false;
               }
            } elseif(count($explode) == 2){
                $condition = $this->queryoptions[$column];
                $trOperator = trim($explode[0]);
                switch($trOperator){
                    case '>' :
    
                        if($condition > $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '<' :
    
                        if($condition < $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '>=' :
    
                        if($condition >= $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '<=' :
    
                        if($condition <= $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
    
                    case '==' :
    
                        if($condition == $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '===' :
    
                        if($condition === $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '!=' :
    
                        if($condition != $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '!==' :
    
                        if($condition !== $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '<>' :
    
                        if($condition <> $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    case '<=>' :
    
                        if($condition <=> $explode[1] ){
                            $this->queryErrors[$column][] = true;
                        } else {
                            $this->errorMatch[$column] = $value;
                            $this->queryErrors[$column][] = false;
                        }
    
                    break;
    
                    default:
                    $this->queryErrors[$column][] = 'not valid operator';
                    break;
                }
            } else{
                if(preg_match($value,  $this->queryoptions[$column])){
                    $this->queryErrors[$column][] = true;
                } else {
                    $this->errorMatch[$column] = $value;
                    $this->queryErrors[$column][] = false;
                }
            }
           
        }
    

        /**
         * @method validate()
         * @description - this method validates values against it's column 
         * requirements
         */
        public function validate() :bool
        {

            //get array of items where columns in $queryoptions matches column in $checklist
            $checkMatches = array_intersect_key($this->checklist, $this->queryoptions);
            
            array_walk($checkMatches, function($value, $column) {

                if(is_array($value)){
                    array_map(function ($realValue) use ( $value,  $column ) {
                        
                        $this->processValidation( $realValue, $column);
                        
                    }, $value);
                } else{
                    $this->processValidation($value, $column);
                }
            });
            
            
            array_walk_recursive( $this->queryErrors, function ($errorState, $column) {
                
                if (!$errorState)
                {
                    $this->isValid[] = false;
                }
                
            }
            );
            
            if(in_array(false, $this->isValid)){
                return false;
            }
            return true;
        }

        public function enter(array $queryoptions) :self
        {
            $this->queryoptions = $queryoptions;
            $this->insertInto($this->queryoptions, $this->tablename);
                
            return $this;

        }

        private function handleErrors()
        {
            $flipregex = $flipregex = array_flip($this->regex);
            
            foreach($this->queryErrors as $column => $errorArray){
                
                foreach($errorArray as $error){
                    if(!$error && in_array($this->errorMatch[$column], $this->regex)){
                    
                        $message = $this->errorTable[$flipregex[$this->errorMatch[$column]]];

                        $this->errorMessage[$column] = $message;
                    } elseif(!$error){
                        $message = $this->errorTable[$this->errorMatch[$column]];
                        
                        $this->errorMessage[$column] = $message;
                    }
                }
            }
        }

        public function save()
        {
            if(empty($this->checklist)){
                $this->commit();
                return true;
            } 
            
            if($this->validate($this->queryoptions)){
                $this->commit();
                return true;
            }
            $this->handleErrors();
            return false;
        }
      }