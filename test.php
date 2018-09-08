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

    $db->deleteRecord([
      'id' => "34",
      'username' => "ee34",
      'emails' => "3dds4"
    ],
    'users',

    "OR"
    
  );
    // $conn = new Connection(new Dbconfig('AbstractIlluminate/ABDatabase/config.json'));
    // // $conn->open();

