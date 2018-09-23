<?php

    require './vendor/autoload.php';
    require 'AbstractIlluminate/ABDev/dev.php';
    
    use AbstractIlluminate\ABDatabase\Connection;
    use AbstractIlluminate\ABDatabase\Database;
    use AbstractIlluminate\ABDatabase\Dbconfig;
    use AbstractIlluminate\ABException\Error;
    $error = new Error();
    $error->useCustomHandler();

    $db = new Database();

    $db->deleteRecord(['username' => 'stanley', 'password' =>'1'], 'users', 'OR')->commit();

  echo $db->sqlQuery;
  formatArray($db->placeholder);
    