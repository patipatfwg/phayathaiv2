<?php

class logsFunction
{
    static function WriteAndroidboxLOG($data_json)
    {
        $datelogs = date("Y-m-d");
        $androidbox_data = $data_json['androidbox'];
        // $filename = "androidboxlogs/".$androidbox_data['device_id']."_".$datelogs.".json";
        $filename = "androidboxlogs/".$androidbox_data['device_id'].".json";
        $file_encode = json_encode($data_json,true);
        file_put_contents($filename, $file_encode );
        chmod($filename,0777);
    }

    static function WriteAPILOG($FLAG_APILOG)
    {
        if( isset($FLAG_APILOG) )
        {
            if( $FLAG_APILOG==1 )
            {

            }
        }
    }
}