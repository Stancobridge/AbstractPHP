<?php

    $arr = [
        'pdf_title | >' => 'JS',
        'pdf_link' => 'https://jszone.com',
    ];
    $keys = array_keys($arr);
    $build = array_reduce($keys, function ($carry, $item) use (&$keys){
        $ex = explode(',', $item);
        if(count($ex) == 2){
            e
        }
    })
    
    // function processValidation($queryoptions, $flipregex, $realValue){

    //     $explode = explode("|", $realValue);
    //     if(in_array($realValue, $flipregex)){

    //        $condition = $this->regex[$realValue];
           
    //        $matchRegex = preg_match($condition, $queryoptions[$column]);
    //        if($matchRegex){
    //             $this->queryErrors[$column][] = true;
    //        } else {
    //             $this->queryErrors[$column][] = false;
    //        }
    //     } elseif(count($explode) == 2){
    //         $condition = $queryoptions[$column];
    //         $trOperator = trim($explode[0]);
    //         switch($trOperator){
    //             case '>' :

    //                 if($condition > $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '<' :

    //                 if($condition < $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '>=' :

    //                 if($condition >= $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '<=' :

    //                 if($condition <= $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;


    //             case '==' :

    //                 if($condition == $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '===' :

    //                 if($condition === $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '!=' :

    //                 if($condition != $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '!==' :

    //                 if($condition !== $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '<>' :

    //                 if($condition <> $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             case '<=>' :

    //                 if($condition <=> $explode[1] ){
    //                     $this->queryErrors[$column][] = true;
    //                 } else {
    //                     $this->queryErrors[$column][] = false;
    //                 }

    //             break;

    //             default:
    //             $this->queryErrors[$column][] = 'not valid operator';
    //             break;
    //         }
    //     }
    //     {

    //     }
    // }
