<?php

class roomFunction
{

    static function GetRoom($FLAG_VIEW)
    {
        include "nurseFunction.php";
        $nurseFunction = new nurseFunction;
    
        //GET Room
        if( $FLAG_VIEW==1 )
        {
            $device_json = trim(file_get_contents("json/androidbox.json"));
            $device_json = json_decode($device_json, true);
            $device_data = $device_json['device'];
            for($getRoom=0;$getRoom<count($device_json['device']);$getRoom++)
            {
                $get_nurse_list=[];
                $device_device_id = $device_data[$getRoom]['device_id'];
                $device_title = $device_json['device'][$getRoom]['title'];
                $device_ordinal = $device_json['device'][$getRoom]['ordinal'];
                // $device_device_id_URL = "androidboxlogs/".$device_device_id.".json";
                $device_device_id_URL = $device_device_id.".json";
                $get_nurse_list = $nurseFunction->NurseLists($device_device_id_URL);
                $DataRoom[$getRoom] = array(
                    "ordinal"=>$device_ordinal,
                    "device_id"=>$device_device_id,
                    "room_title"=>$device_title,
                    "nurse_list"=>$get_nurse_list
                );
                if( file_exists($device_device_id_URL) )
                {
                    unlink($device_device_id_URL);  
                }
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
            $GetDataAPI = [
                "head"=>array("code"=>200,"message"=>"OK"),
                "body"=>array("room"=>$DataRoom)
            ]; 
            $filenameGetDataAPI = "GetDataAPI.json";
            $file_encodeGetDataAPI = json_encode($GetDataAPI,true);
            file_put_contents($filenameGetDataAPI, $file_encodeGetDataAPI );
            chmod($filenameGetDataAPI,0777);  
            //
            // unlink($filenameGetDataAPI);
            //
    
            return $GetDataAPI;
    
        }
        //
    }

}