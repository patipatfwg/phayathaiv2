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

// $dataFunction->writeJSON(1,$itag_data);
    GetRoom();

//
//
//


function GetRoom()
{
    //GET Room
    if( !isset($FLAG_GetDataAPI) )
    {
        $device_json = trim(file_get_contents("androidbox.json"));
        $device_json = json_decode($device_json, true);

        for($getRoom=0;$getRoom<count($device_json['device']);$getRoom++)
        {
            $get_nurse_list=[];
            $device_device_id = $device_json['device'][$getRoom]['device_id'];
            $device_title = $device_json['device'][$getRoom]['title'];
            $device_ordinal = $device_json['device'][$getRoom]['ordinal'];

            $device_device_id_URL = "androidboxlogs/".$device_device_id.".json";
            if( file_exists($device_device_id_URL) )
            {
                $iTAG_json = trim(file_get_contents($device_device_id_URL));
                $iTAG_json = json_decode($iTAG_json, true);
                $get_nurse_list = [];
                
                for($getNurse=0;$getNurse<count($iTAG_json);$getNurse++)
                {
                    $uuid = $iTAG_json[$getNurse]['uuid'];
                    $mac_address = $iTAG_json[$getNurse]['mac_address'];
                    $distance = $iTAG_json[$getNurse]['distance'];
                    $title = $iTAG_json[$getNurse]['title'];
                    
                    if( strstr( $title,"iTAG"))
                    {
                        if( $distance>(-80.0) )
                        {
                            $get_nurse_list[$getNurse] = array(
                                'uuid'=>$uuid,
                                'mac_address'=>$mac_address,
                                'distance'=>$distance,
                                'title'=>$title,
                            );
                        }
                    }

                    // if( strstr( $title,"iTAG") || $title=="Redmi AirDots_L" )
                    // {
                    //     if( $distance>(-80.0) )
                    //     {
                    //         $get_nurse_list[$getNurse] = array(
                    //             'mac_address'=>$mac_address,
                    //             'distance'=>$distance,
                    //             'title'=>$title,
                    //         );
                    //     }
                    // }
                } 

                //Sort
                sort($get_nurse_list);
                foreach ($get_nurse_list as $key => $val) {
                    $get_nurse_list[$key] = array(
                        'uuid'=>$uuid,
                        'mac_address'=>$val['mac_address'],
                        'distance'=>$val['distance'],
                        'title'=>$val['title'],
                    );
                }
                //

            }
            if(!isset($get_nurse_list)){ $get_nurse_list=[]; }
            $DataRoom[$getRoom] = array(
                                        "ordinal"=>$device_ordinal,
                                        "device_id"=>$device_device_id,
                                        "room_title"=>$device_title,
                                        "nurse_list"=>$get_nurse_list
                                    );

        }

        //Sort
        sort($DataRoom);
        foreach ($DataRoom as $key => $val) {
            $DataRoom[$key] = array(
                "ordinal"=>$val['ordinal'],
                "device_id"=>$val['device_id'],
                "room_title"=>$val['room_title'],
                "nurse_list"=>$val['nurse_list']
            );
        }
        //
        if(count($DataRoom)>1)
        {
            $DataRoom = $DataRoom;
        }
        else
        {
            $DataRoom = [$DataRoom];
        }
        // $RefURL = array("https://www.gujarattourism.com/file-manager/ebrochure/thumbs/testing_e_brochure_3.pdf","http://www3.eng.psu.ac.th/pec/6/pec6/paper/CoE/PEC6OR170.pdf","https://forums.estimote.com/t/use-rssi-measure-the-distance/3665/3");
        $GetDataAPI = [
            "head"=>array("code"=>200,"message"=>"OK"),
            "body"=>array("room"=> $DataRoom )
            // ,"footer"=>array("Ref."=>$RefURL)
        ]; 
        $filenameGetDataAPI = "json/GetDataAPI.json";
        $file_encodeGetDataAPI = json_encode($GetDataAPI,true);
        file_put_contents($filenameGetDataAPI, $file_encodeGetDataAPI );
        chmod($filenameGetDataAPI,0777);  
        //
        // unlink($device_device_id_URL);
        //
    }
    //

}

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