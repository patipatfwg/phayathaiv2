<?php 
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

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
        //Androidbox
        if( isset($data_json['androidbox']) && isset($data_json['androidbox']['device_id']) )
        { 
            $FLAG_PARAMS = 1;
            $androidbox_data = $data_json['androidbox']; 
            $deviceId = $androidbox_data['device_id'];
        }
        else if( isset($data_json['information']) && isset($data_json['androidbox']['deviceId']) )
        {
            $FLAG_PARAMS = 1;
            $androidbox_data = $data_json['information'];
            $deviceId = $androidbox_data['deviceId'];
        }
        else
        {
            $FLAG_PARAMS = 0;
        }

        //iTAG
        if(isset($data_json['itag']))
        { 
            $FLAG_PARAMS2 = 1;
            $itag_data = $data_json['itag']; 
        }
        else if(isset($data_json['nurse']))
        {
            $FLAG_PARAMS2 = 1;
            $itag_data = $data_json['nurse'];
        }
        else
        {
            $FLAG_PARAMS2 = 0;
        }

        //List
        if(isset($itag_data['list']))
        { 
            $FLAG_PARAMS_LIST = 1;
            $itag_list_data = $itag_data['list']; 
        }
        else
        {
            $FLAG_PARAMS_LIST = 0;
        }

        //Version
        if(isset($itag_data['version']))
        { 
            $FLAG_PARAMS_VERSION = 1;
            $itag_version_data = $itag_data['version']; 
        }
        else
        {
            $FLAG_PARAMS_VERSION = 0;
        }
        
        if( $FLAG_PARAMS==1 && $FLAG_PARAMS2==1 && $FLAG_PARAMS_VERSION==1 )
        {
            $isAndroidbox = $dataFunction->isAndroidbox($deviceId);
            if($isAndroidbox==1)
            {
                $GetiTAGversion = $dataFunction->CheckVersionAndGetLists('itag','checkversion');
                if( $FLAG_PARAMS_LIST==1 )
                {
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
                else
                {
                    $code = 200;
                    $message = "Send Success => NO HAVE PARAMS LIST";
                    $version = $GetiTAGversion;
                    $data = [];
                }

                if($FLAG_WRITEJSON==1)
                {
                    $WRITEJSON = ["footer"=>$dataFunction->WriteAndroidboxLOG($data_json)];
                }
                else
                {
                    $WRITEJSON = [];
                    $dataFunction->WriteAndroidboxLOG($data_json);
                }

                if($FLAG_VIEW==1)
                {
                    $GetRoom = ["footer"=>$roomFunction->GetRoom($FLAG_VIEW)];
                }
                else
                {
                    $GetRoom = [];
                }

                $data = $data + $GetRoom + $WRITEJSON;

            }
            else
            {
                $code = 200;
                $message = "Send Success => NO HAVE ANDROIDBOX";
                $version = 'xxxx2020xxxxx';
                $data = [];
            }
        }
        else
        {
            $code = 400;
            $message = "NOT PARAMS $FLAG_PARAMS $FLAG_PARAMS2 $FLAG_PARAMS_VERSION  => KICK KICK!!!";
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

//Write Log
$filename = $deviceId."_data_detect_log.json";
$file_encode = json_encode($data_json,true);
file_put_contents($filename, $file_encode );
chmod($filename,0777);  


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