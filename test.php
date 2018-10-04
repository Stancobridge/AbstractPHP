<?php

    require './vendor/autoload.php';
    require 'AbstractIlluminate/ABDev/dev.php';
    
    use AbstractIlluminate\ABDatabase\Connection;
    use AbstractIlluminate\ABDatabase\Database;
    use AbstractIlluminate\ABDatabase\Model;
    use AbstractIlluminate\ABDatabase\Dbconfig;
    use AbstractIlluminate\ABException\Error;
    $error = new Error();
    $error->useCustomHandler();
    
    
    $db = new Database();
    $db->deleteRecord([
      'id' => [[155, 156, 157, 158, 159, 160, ], 'or']
    ],
    'pdf',
    'OR'
  );
    $db->commit();
    echo $db->sqlQuery;

    formatArray($db->placeholder);

    // $db = new Model();
    // $db->tablename = 'pdf';
    // $db->checklist = [
    //   'pdf_size' => ['int', 'number', '> | 4'],
    //   'pdf_link' => ["> | 45565", 'int'],
    //   'pdf_title' => ['text', 'not_null'],
    //   'pdf_crawl_date' => ['int', '< | 567']
    // ];

    // $db->errorTable = [
    //   'number' => 'Sorry, only numbers are allowed in this field',
    //   'int' => 'This field must contain a valid integer',
    //   'alphanumeric' => 'This field can only contain alphabet or number but no space',
    //   '> | 546' => 'This field must be greater than 546',
    //   '< | 567' => 'This field must be less than 567',
    //   'text' => 'An invalid character found',
    //   "> | 45565" => 'This field must be greater than 45565',
    //   'not_null' => 'This field cannot be empty'
    // ];
    
    // $db->enter([
    //   'pdf_title' => 'PHP EXPERT WITH PROJECTS',
    //   'pdf_size' => '345334',
    //   'pdf_link' => '635464454',
    //   'pdf_crawl_date' => '456'
    // ]);

    // if($db->save()){
    //   echo 'updated';
    // } else{
    //   echo 'error';
    // }

    // formatArray($db->queryErrors);
    // formatArray($db->errorMessage);
    
    

    