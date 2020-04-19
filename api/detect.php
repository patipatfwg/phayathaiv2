<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);
set_time_limit(0);

// session_start();
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
        if(isset($data_json['iTAG']))
        { 
            $FLAG_PARAMS2 = 1;
            $itag_data = $data_json['iTAG']; 
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

        //List
        if(isset($itag_data['itag_list']))
        { 
            $FLAG_PARAMS_LIST = 1;
            $itag_list_data = $itag_data['itag_list']; 
        }
        else
        {
            $FLAG_PARAMS_LIST = 0;
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
                        $itag_list = ["itag_list"=>null];
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
            $data = [$data_json];
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

unset($code,$message,$version,$data);

//Write Log
$filename = $deviceId."_data_detect_log.json";
$file_encode = json_encode($data_json,true);
file_put_contents($filename, $file_encode );
chmod($filename,0777);