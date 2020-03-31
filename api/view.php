<?php

session_start();

header('Content-Type: application/json');

$headers_Authorization = $_SERVER['HTTP_AUTHORIZATION'];

// $content = trim(file_get_contents("php://input"));
// $data_json = json_decode($content, true);


if($_SERVER['REQUEST_METHOD']=='POST')
{
    if($headers_Authorization=='Phayathai')
    {

        $filename = "json/GetDataAPI.json";
        $GetDataAPI = trim(file_get_contents($filename));
        $GetDataAPI = json_decode($GetDataAPI, true);
        echo json_encode($GetDataAPI);
        //
        unlink($filename);
        //
    }
}