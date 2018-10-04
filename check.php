<?php

$arrayink = [
    'id' => 3430,
    'name' => 'Mardsk',
    'colsor' => 'red',
];

$arrays = [
    'id' => 342330,
    'name' => 'Masadrdsk',
    'color' => 'reasdd',
];

print_r(array_intersect_key($arrayink, $arrays));







// public function validate(array $queryoptions) :bool
        // {
        //     $isvalid = false;
        //     $check = array_intersect_key($this->checklist, $queryoptions);
        //     array_walk($check, function ($item, $key) use ($queryoptions, &$isvalid){
        //         //check value from check list
        //         $flipregex = array_flip($this->regex);
        //         $checkExp = null;
                
        //         if(is_array($item)){
                    
        //             foreach ($item as $condition) {
                        
        //                 $equality = explode('|', $condition);
        //                 $checkValue = $queryoptions[$key];
        //                 if(in_array($condition, $flipregex)){
                            
        //                     $this->queryErrors[$key] = $condition;
                            
        //                     $getRegex = $this->regex[$condition];
                            
        //                     $checkExp = preg_match($getRegex, $checkValue);
        //                     if($checkExp){
        //                         trigger_error('error here');
        //                     }

        //                 } elseif(count($equality)){

        //                     $operator = trim($equality[0]);
        //                     switch ($operator) {
        //                         case ">":
        //                             if ($checkValue > $equality[1]){

        //                                 $checkExp = true;
        //                             } else{
                                        
        //                                 $checkExp = false;
        //                             }
        //                             break;
                                
        //                         default:
        //                             echo "EVENTHOUGH DEM NO LIKE";
        //                             break;
        //                     }
        //                 }
        //             }
                    
        //         } elseif(in_array($item, $flipregex)){
        //             $getRegex = $this->regex[$item];
        //             $checkValue = $queryoptions[$key];
        //             $checkExp = preg_match($getRegex, $checkValue);
        //         } else{
        //             $getRegex = $item;
        //             $checkValue = $queryoptions[$key];
        //             $checkExp = preg_match($getRegex, $checkValue);
                    
        //         }
                
        //         if($checkExp){
        //             $isvalid = true;
        //         } else{
        //             trigger_error('invalid validation rule passed to ['.$key.']');
        //         }
        //     });
        //     return $isvalid;
        // }