<?php

    require './vendor/autoload.php';
    
    use AbstractIlluminate\ABException\Error;
    $error = new Error();
    $error->useCustomHandler();
    strpos();
    $error->errorLog('files.txt');

    // echo password_hash("@CRYPTO111", PASSWORD_DEFAULT);