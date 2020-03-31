<?php
class dataFunction
{
    function CheckVersionAndGetLists($KIND,$FLAG_TYPE)
    {
        if($KIND=='androidbox')
        {
            $filename = 'androidbox.json';
        }
        else if($KIND=='itag')
        {
            $filename = 'itag.json';
        }

        $content = trim(file_get_contents($filename));
        $data_json = json_decode($content, true);
        $androidbox_data = $data_json; 

        if($FLAG_TYPE=='checkversion')
        {
            $version = $androidbox_data['version'];
            $data = $version;
        }
        else if($FLAG_TYPE=='getlists')
        {
            $device = $androidbox_data['device'];
            for($num=0;$num<count($device);$num++)
            {
                if($KIND=='androidbox')
                {
                    $data[$num] = array(
                        'device_id'=> $device[$num]['device_id'],
                        'title'=> $device[$num]['title']
                    );
                }
                else if($KIND=='itag')
                {
                    $data[$num] = array(
                        'mac_address'=> $device[$num]['mac_address'],
                        'uuid'=> '0000000000000'
                        // ,'title'=> $device[$num]['title']
                    );
                }
            }
        }
        return $data;
    }

    /*
    function GetVersionAndroidbox()
    {
        $content = trim(file_get_contents("androidbox.json"));
        $data_json = json_decode($content, true);
        $androidbox_data = $data_json; 
        $version = $androidbox_data['version'];
        return $version;
    }

    function GetListAndroidbox()
    {
        $content = trim(file_get_contents("androidbox.json"));
        $data_json = json_decode($content, true);
        $androidbox_data = $data_json; 
        $device = $androidbox_data['device'];

        for($num=0;$num<count($device);$num++)
        {
            $data[$num] = array(
                'device_id'=> $device[$num]['device_id'],
                'title'=> $device[$num]['title']
            );
        }
        return $data;
    }

    function GetVersioniTAG()
    {
        $content = trim(file_get_contents("itag.json"));
        $data_json = json_decode($content, true);
        $itag_data = $data_json; 
        $version = $itag_data['version'];
        return $version;
    }

    function GetListiTAG()
    {
        $content = trim(file_get_contents("itag.json"));
        $data_json = json_decode($content, true);
        $itag_data = $data_json; 
        $device = $itag_data['device'];

        for($num=0;$num<count($device);$num++)
        {
            $data[$num] = array(
                'mac_address'=> $device[$num]['mac_address'],
                'title'=> $device[$num]['title']
            );
        }
        return $data;
    }
    */

    function WriteGetDataJSON()
    {
        // if( isset($FLAG_BACKEND) )
        // {
        //     if( $FLAG_BACKEND==1 )
        //     {
        //         //Backend
        //         $filename = "json/".$information_data['deviceId'].".json";
        //         $file_encode = json_encode($data_input,true);
        //         file_put_contents($filename, $file_encode );
        //         chmod($filename,0777); 
                    
        //             //GET Room
        //             if( !isset($FLAG_GetDataAPI) )
        //             {
        //                 $device_json = trim(file_get_contents("device.json"));
        //                 $device_json = json_decode($device_json, true);
            
        //                 for($getRoom=0;$getRoom<count($device_json['device']);$getRoom++)
        //                 {
        //                     $get_nurse_list=[];
        //                     $device_deviceId = $device_json['device'][$getRoom]['deviceId'];
        //                     $device_title = $device_json['device'][$getRoom]['title'];
        //                     $device_ordinal = $device_json['device'][$getRoom]['ordinal'];
        //                     $device_deviceId_URL = "json/".$device_deviceId.".json";
            
        //                     if( file_exists($device_deviceId_URL) )
        //                     {
        //                         $iTAG_json = trim(file_get_contents($device_deviceId_URL));
        //                         $iTAG_json = json_decode($iTAG_json, true);
        //                         $get_nurse_list = [];
                                    
        //                         for($getNurse=0;$getNurse<count($iTAG_json);$getNurse++)
        //                         {
        //                             $mac_address = $iTAG_json[$getNurse]['mac_address'];
        //                             $distance = $iTAG_json[$getNurse]['distance'];
        //                             $title = $iTAG_json[$getNurse]['title'];
            
        //                             // $distance_cal = ($distance + 49.751)/-1.2824;
        //                             // $distance_rating_5m = -56.38;
        //                             // $distance_rating_1m = -51.45;
            
        //                             if( strstr( $title,"iTAG") || $title=="Redmi AirDots_L" )
        //                             {
        //                                 if( $distance>(-80.0) )
        //                                 {
        //                                     $get_nurse_list[$getNurse] = array(
        //                                         'mac_address'=>$mac_address,
        //                                         'distance'=>$distance,
        //                                         'title'=>$title,
        //                                     );
        //                                 }
        //                             }
        //                         } 
            
        //                         //Sort iTAG
        //                         sort($get_nurse_list);
        //                         foreach ($get_nurse_list as $key => $val) 
        //                         {
        //                             $get_nurse_list[$key] = array(
        //                                 'mac_address'=>$val['mac_address'],
        //                                 'distance'=>$val['distance'],
        //                                 'title'=>$val['title'],
        //                             );
        //                         }
            
        //                     }
                                
        //                     if(!isset($get_nurse_list)){ $get_nurse_list=[]; }
        //                     $DataRoom[$getRoom] = array(
        //                         "ordinal"=>$device_ordinal,
        //                         "deviceId"=>$device_deviceId,
        //                         "room_title"=>$device_title,
        //                         "nurse_list"=>$get_nurse_list
        //                     );
            
        //                 }
            
        //                     //Sort
        //                     sort($DataRoom);
        //                     foreach ($DataRoom as $key => $val) {
        //                         $DataRoom[$key] = array(
        //                             "ordinal"=>$val['ordinal'],
        //                             "deviceId"=>$val['deviceId'],
        //                             "room_title"=>$val['room_title'],
        //                             "nurse_list"=>$val['nurse_list']
        //                         );
        //                     }
        //                     //
        //                     if(count($DataRoom)>1)
        //                     {
        //                         $DataRoom = $DataRoom;
        //                     }
        //                     else
        //                     {
        //                         $DataRoom = [$DataRoom];
        //                     }
        //                     // $RefURL = array("https://www.gujarattourism.com/file-manager/ebrochure/thumbs/testing_e_brochure_3.pdf","http://www3.eng.psu.ac.th/pec/6/pec6/paper/CoE/PEC6OR170.pdf","https://forums.estimote.com/t/use-rssi-measure-the-distance/3665/3");
        //                     $GetDataAPI = [
        //                         "head"=>array("code"=>200,"message"=>"OK"),
        //                         "body"=>array("room"=> $DataRoom )
        //                         // ,"footer"=>array("Ref."=>$RefURL)
        //                     ]; 
        //                     $filenameGetDataAPI = "json/GetDataAPI.json";
        //                     $file_encodeGetDataAPI = json_encode($GetDataAPI,true);
        //                     file_put_contents($filenameGetDataAPI, $file_encodeGetDataAPI );
        //                     chmod($filenameGetDataAPI,0777);  
        //                     //
        //                     unlink($device_deviceId_URL);
        //                     //
        //             }
        //                 //
                    
        //             // echo json_encode($GetDataAPI);
        //     }
        // }    
    }
}
