<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

include "dataFunction.php";
include "roomFunction.php";
$dataFunction = new dataFunction;
$roomFunction = new roomFunction;

$content = trim(file_get_contents("php://input"));
$data_json = json_decode($content, true);

$FLAG_WRITEJSON = 0;
$FLAG_VIEW = 0;
$FLAG_APILOG = 0;

if($_SERVER['REQUEST_METHOD']=='POST')
{
    if( isset($data_json) )
    {
        if( isset($data_json['itag']) && isset($data_json['androidbox']) )
        {
            $itag_data = $data_json['itag'];
            $androidbox_data = $data_json['androidbox'];
            $GetiTAGversion = $dataFunction->CheckVersionAndGetLists('itag','checkversion');

            if( isset($itag_data['list']) )
            {
                $itag_version_data = $itag_data['version'];
                if($GetiTAGversion!=$itag_version_data)
                {
                    $itag_list = $dataFunction->CheckVersionAndGetLists('itag','getlists');
                    $itag_list = ["itag_list"=>$itag_list];
                }
                else
                {
                    $itag_list = [];
                }
                $code = 200;
                $message = "Send Success";
                $version = $GetiTAGversion;
                $data = $itag_list;                
            }
            else if( isset($itag_data['list']) )
            {
                $code = 200;
                $message = "Send Success => NO HAVE PARAMS LIST";
                $version = $GetiTAGversion;
                $data = [];
            }
            $FLAG_WRITEJSON = 1;
            $FLAG_VIEW = 0;
            $FLAG_APILOG = 0;
            if($FLAG_WRITEJSON==1)
            {
                $dataFunction->WriteAndroidboxLOG($data_json);
            }            
        }
        else
        {
            $code = 400;
            $message = "NOT PARAMS => KICK KICK!!!";
            $version = 'xxxx2020xxxxx';
            $data = [];
        }
        //End
    }
    else
    {
        $code = 500;
        $message = "BODY RAW JSON => KICK KICK!!!";
        $version = 'xxxx2020xxxxx';
        $data = [];
    }
}
else if($_SERVER['REQUEST_METHOD']=='GET')
{
    $code = 400;
    $message = "METHOD WHAT => KICK KICK!!!";
    $version = 'xxxx2020xxxxx';
    $data = [];
}

if($FLAG_VIEW==1)
{
    $GetRoom = ["footer"=>$roomFunction->GetRoom($FLAG_VIEW)];
}
else
{
    $GetRoom = [];
}

$data = [
    "head"=>array("code"=>$code,"message"=>$message,"version"=>$version),
    "body"=>$data
];

$data = $data + $GetRoom;

echo json_encode($data,JSON_PRETTY_PRINT);
 
//
// 
//Backend
// 
//


// function Main()
// {
//     // $itag_data = $data_json['itag'];
//     // $androidbox_data = $data_json['androidbox'];
//     $write_device_id = $androidbox_data['device_id'];
//     if($write_device_id!='')
//     {
//         //Write Log
//         $filename = "jsonlogs/".$information_data['device_id']."_data_detect.json";
//         $file_encode = json_encode($data_json,true);
//         file_put_contents($filename, $file_encode );
//         chmod($filename,0777);     
         
//         //
//         // for($num=0;$num<count($nurse_data);$num++)
//         // {
//         //     $title = $nurse_data[$num]['title'];
//         //     if($title=='iTAG            ')
//         //     {
//         //         $mac_address = $nurse_data[$num]['mac_address'];
//         //         $distance = $nurse_data[$num]['distance'];
//         //         $data_input = [array(  'mac_address'=> $mac_address,'title'=> $title,'distance'=> $distance)];
//         //     }
//         // }

//         for($num=0;$num<count($nurse_data);$num++)
//         {
//             if( isset($nurse_data[$num]['title']) ){$title = $nurse_data[$num]['title'];}else{$title =null;}
          
//             $uuid = $nurse_data[$num]['uuid'];
//             $mac_address = $nurse_data[$num]['mac_address'];
//             $distance = $nurse_data[$num]['distance'];
//             $data_input[$num] = array(   'uuid'=> $uuid,'mac_address'=> $mac_address,'title'=> $title,'distance'=> $distance);
            
//         }
        
//         $data = [
//             "head"=>array("code"=>200,"message"=>"OK","version"=>''),
//             "body"=>array( "iTAG"=> $data_input )
//         ];
        
//     }
//     else
//     {
//         $data = [
//             "head"=>array("code"=>200,"message"=>"Thank You Pong","version"=>''),
//             "body"=>[]
//         ];   
//     }
// } 

/*

function Main()
function Write JSON Logs()
function Write API Logs()
function Write Get Data JSON()

*/