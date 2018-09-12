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

    $db->selectInto(['user | >' => 'stanley'],'users');

  echo $db->sqlQuery;
  formatArray($db->placeholder);
    // $conn = new Connection(new Dbconfig('AbstractIlluminate/ABDatabase/config.json'));
    // // $conn->open();

// $condition = $condition;

            // $this->placeholder = $this->getPlaceholders($queryoptions);

            // //get the query values
            // $this->values = $this->getValues($this->placeholder);

            // // //get the query columns
            // $this->columns = $this->getColumns($queryoptions);
            
            // //get array of columns
            // $keys = array_keys($queryoptions);

            // $condition = $this->getConditions($queryoptions, $this->isBool($condition));
            // //creates the where clause of this delete
            // $where = array_reduce($keys, function ($carry, $item) use ($condition) {
            //     return !$carry ? " {$condition} {$item} = :{$item} " : $carry ." {$condition} {$item} = :{$item}";
            // });

            // $this->sqlQuery .= " {$where}";

            // return $this;