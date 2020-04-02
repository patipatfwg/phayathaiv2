<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

include "dataFunction.php";
include "roomFunction.php";
$dataFunction = new dataFunction;
$roomFunction = new roomFunction;

$headers_Authorization = $_SERVER['HTTP_AUTHORIZATION'];

if($_SERVER['REQUEST_METHOD']=='POST')
{
    if($headers_Authorization=='phayathai@freewill')
    {
        $FLAG_VIEW=1;
        if($FLAG_VIEW==1)
        {
            $roomFunction->GetRoom($FLAG_VIEW);
        }
        //Read
        $filename = "json/GetDataAPI.json";
        $GetDataAPI = trim(file_get_contents($filename));
        $GetDataAPI = json_decode($GetDataAPI, true);
        echo json_encode($GetDataAPI);
    }
}
else
{
    $code = 400;
    $message = "METHOD WHAT => KICK KICK!!!";
    $version = 'xxxx2020xxxxx';
    $data = [
        "head"=>array("code"=>$code,"message"=>$message,"version"=>$version),
        "body"=>[]
    ];
    echo json_encode($data);
}