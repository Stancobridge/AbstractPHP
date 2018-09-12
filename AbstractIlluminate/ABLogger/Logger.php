<?php
use AbstractIlluminate\Psr\Log as LogL;
namespace AbstractIlluminate\ABLogger;

    class Logger implements \AbstractIlluminate\Psr\Log\LoggerInterface{
        private $message = NULL;
        private $context = NULL;
        protected function emergency($message, array $context = []){
            if(gettype($message) == 'string'){
                $this->message = $message;
                $this->context = $context;

                $this->log(LogL\LogLevel::EMERGENCY);
            }
        }
    }