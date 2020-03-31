<?php 

session_start();
header('Content-Type: application/json');
include "logsFunction.php";
include "dataFunction.php";

$logsFunction = new logsFunction;
$dataFunction = new dataFunction;
$content = trim(file_get_contents("php://input"));
$data_json = json_decode($content, true);

$FLAG_WRITEJSON = 0;
$FLAG_APILOG = 0;

if($_SERVER['REQUEST_METHOD']=='POST')
{
    if( isset($data_json) )
    {
        $logsFunction->WriteAndroidboxLOG($data_json);

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

        }
        else
        {
            $code = 400;
            $message = "NOT PARAMS => KICK KICK!!!";
            $version = 'xxxx2020xxxxx';
            $data = [];
        }
        $FLAG_WRITEJSON = 1;
        $FLAG_APILOG = 0;
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
    $message = "POST METHOD => KICK KICK!!!";
    $version = 'xxxx2020xxxxx';
    $data = [];
}

$data = [
    "head"=>array("code"=>$code,"message"=>$message,"version"=>$version),
    "body"=>$data
];

echo json_encode($data,JSON_PRETTY_PRINT);
 
//
// 
//Backend
// 
// 

// writeJSON($FLAG_WRITEJSON,$itag_data);


//WriteLogs
// $logsFunction->WriteAndroidboxLOG($data_json);

function writeJSON($FLAG_WRITEJSON,$itag_data)
{
    if($FLAG_WRITEJSON==1)
    {
        if(count($itag_data)>0)
        {
            $GetDataAPI = array("Count"=>count($itag_data));
            $filenameGetDataAPI = "json/DataJSON.json";
            $file_encodeGetDataAPI = json_encode($GetDataAPI,true);
            file_put_contents($filenameGetDataAPI, $file_encodeGetDataAPI );
            chmod($filenameGetDataAPI,0777); 
        }
    }
}


// function Main()
// {
//     // $itag_data = $data_json['itag'];
//     // $androidbox_data = $data_json['androidbox'];
//     $write_deviceId = $androidbox_data['deviceId'];
//     if($write_deviceId!='')
//     {
//         //Write Log
//         $filename = "jsonlogs/".$information_data['deviceId']."_data_detect.json";
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

ตารางงานสัปดาห์นี้ (30/03/63 - 03/04/63)
(W@H)
Phayathai Project
+ Develop Client API Androidbox
    - Function Check Version and Get Lists Androidbox
    - Function Check Version and Get Lists iTAG
    - Function Get 
    - Function Write Get Data JSON   

    - Function Write iTAG Logs
    - Function Write API Logs

+ Develop Client API ส่งระยะทางของกระดิ่ง
+ Develop Client API 
+ Develop Server API ส่งข้อมูลทาง
+ UPDATE Production CUDM

*/

/*

function Main()
function Write JSON Logs()
function Write API Logs()
function Write Get Data JSON()

*/