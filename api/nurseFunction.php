<?php

class nurseFunction
{
    static function NurseListss($device_device_id_URL)
    {
        $nurseFunction = new nurseFunction;
        $dataFunction = new dataFunction;

        if( file_exists($device_device_id_URL) )
        {
            $iTAG_json = trim(file_get_contents($device_device_id_URL));
            $iTAG_json = json_decode($iTAG_json, true);
            for($getNurse=0;$getNurse<count($iTAG_list);$getNurse++)
            {
                // $uuid = $iTAG_list[$getNurse]['uuid'];
                $mac_address = $iTAG_list[$getNurse]['mac_address'];
                $distance = $iTAG_list[$getNurse]['distance'];
                $title = $dataFunction->GetTitle($mac_address);
            }
        }

        if(!isset($get_nurse_list)){ $get_nurse_list=[]; }
        return $get_nurse_list;
    }

    static function NurseLists($device_device_id_URL)
    {
        $nurseFunction = new nurseFunction;
        $dataFunction = new dataFunction;

        if( file_exists($device_device_id_URL) )
        {
            $iTAG_json = trim(file_get_contents($device_device_id_URL));
            $iTAG_json = json_decode($iTAG_json, true);

            // if( isset($iTAG_json['iTAG']['itag_list']) )
            // {
            //     $iTAG_list = $iTAG_json['iTAG']['itag_list'];
            // }

            if( isset($iTAG_json['itag']['itag_list']) )
            {
                $iTAG_list = $iTAG_json['itag']['itag_list'];
            }

            $get_nurse_list = [];
            
            for($getNurse=0;$getNurse<count($iTAG_list);$getNurse++)
            {
                // $uuid = $iTAG_list[$getNurse]['uuid'];
                $mac_address = $iTAG_list[$getNurse]['mac_address'];
                $distance = $iTAG_list[$getNurse]['distance'];
                $title = $dataFunction->GetTitle($mac_address);

                /*
                $FLAG_DISTANCE = $dataFunction->FilterDistance($uuid,$distance);
                if( $FLAG_DISTANCE==1 )
                {
                    $get_nurse_list[$getNurse] = array(
                        'uuid'=>$uuid,
                        'mac_address'=>$mac_address,
                        'distance'=>$distance
                    );
                }
                */

                $FLAG_DISTANCE_MAC = $dataFunction->FilterDistanceMAC($mac_address,$distance);
                // if( $FLAG_DISTANCE_MAC==1 )
                // {
                    $get_nurse_list[$getNurse] = array(
                        'mac_address'=>$mac_address,
                        'distance'=>$distance,
                        'title'=>$title
                    );
                // }
                
            }

            $get_nurse_list = $nurseFunction->SortNurse($get_nurse_list);

        }
        if(!isset($get_nurse_list)){ $get_nurse_list=[]; }
        return $get_nurse_list;

    }

    //Output
    public function SortNurse($get_nurse_list)
    {
        //Sort
        sort($get_nurse_list);
        foreach ($get_nurse_list as $key => $val) {
            $get_nurse_list[$key] = array(
                // 'uuid'=>$val['uuid'],
                'mac_address'=>$val['mac_address'],
                'distance'=>$val['distance'],
                'title'=>$val['title'],
            );
        }
        return $get_nurse_list;
    }
}

                //Sort
                // sort($get_nurse_list);
                // foreach ($get_nurse_list as $key => $val) {
                //     $get_nurse_list[$key] = array(
                //         'uuid'=>$uuid,
                //         'mac_address'=>$val['mac_address'],
                //         'distance'=>$val['distance'],
                //         'title'=>$val['name'],
                //     );
                // }
                //