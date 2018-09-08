<?php
    namespace AbstractIlluminate\ABInterface;

    interface ErrorInterface{
        public function errorText();
        public function errorHtml();
        public function errorLog($filename = null, $database = null);

    }