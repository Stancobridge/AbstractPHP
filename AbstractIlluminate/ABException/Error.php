<?php
    
    namespace AbstractIlluminate\ABException;
    use AbstractIlluminate\ABInterface as ErrorFace;

    class Error extends \Error /*implements ErrorFace\ErrorInterface*/{

        protected $message = null;
        protected $code = null;
        protected $file = null;
        protected $line = null;
        protected $errorFile = null;
        protected $type = null;
        public $previous = null;
        
        public function __construct($message = null, $code = 0, $file = null, $line = null, \Error $previous = null){
            $this->message = $message;
            $this->code = $code;
            $this->previous = $previous;
            $this->file = $file;
            $this->line = $line;
            parent::__construct($message, $code, $previous);
        }

        public function dispatchError(){
           echo $this->buildErrorTag($this->message, $this->line, $this->file, $this->code, $this->type);
        }
        private function buildErrorTag($message = null, $line = null, $file = null, $code = null, $error_type = null){
            $message = "<div style='background: black;  color: lightgreen; font-weight: bold; font-size: 14px; padding: 10px; max-width: 540px; margin: auto'>
            <h3 style='border-bottom: 1px solid silver; margin: 0px;'>Error Type: <span style='color: red'> {$error_type} </span> </h3>        
                    <p>
                      <p style='padding: 2px; padding-left: 0px; border-bottom: 1px solid silver'> <span style='color: white'> Error Message: </span>   {$message} </p> 
                      <p style='padding: 2px; padding-left: 0px; border-bottom: 1px solid silver'> <span style='color: white'> Error Line: </span>   {$line} </p> 
                      <p style='padding: 2px; padding-left: 0px; border-bottom: 1px solid silver'> <span style='color: white'> Error File: </span>  <span style='color: red'> {$file} </span> </p> 
                    </p>
                    <p style='padding: 2px; padding-left: 0px; border-bottom: 1px solid silver'> <span style='color: white'> Error Code: </span>  <span style='color: '> {$code} </span> </p> 
                    </p>
            
            </div>";

            return $message;
        }
        
        public function errorText(){
            return $this->message;
        }

        public function errorHtml(){
            return "<b>Error: ".$this->message;
        }

        

        public function useCustomHandler(){
            \set_error_handler([&$this, 'errorHandler'],  E_ALL);
            \set_exception_handler([&$this, 'exceptionHandler']);
        }
        public function errorHandler($code, $message, $file, $line) {
            $this->message = $message;
            $this->code = $code;
            $this->file = $file;
            $this->line = $line;
            
            echo $this->buildErrorTag($message, $line, $file, $code, 'Error');

        }


        public function errorLog($filename = null, \PDO $database = null){
            if(empty($this->errorFile)){
                $this->errorFile = $filename;
            }
            if(empty($this->message)){
                //Do nothing
            } else{
                if(\file_exists($filename) && \is_readable($filename)){
                    $errormsg = "Error Message: ".$this->message."\nTime: ".date("D:M:Y - h:m:s") ."\nFile: {$this->file} \nLine: {$this->line}\n \n";
                    $file = \fopen($filename, "a+");
                    \fwrite($file, $errormsg);
                    \fclose($file);
                } else{
                    throw new $this('File Not Found');
                }
            }
            
        }

        public function exceptionHandler($exception){
            
            echo $this->buildErrorTag($exception->getMessage(), $exception->getLine(), $exception->getFile(), $exception->getCode(), 'Exception');

        }


        


    }
