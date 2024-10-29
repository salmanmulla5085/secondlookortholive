<?php
session_start();        

        $data = [];
        
        $Client_ID = 'AnLNQvlvSCSM7UrvjdrNHQ';
        $Client_Secret = 'Wo17YvH1XP2BTT90RLYMrGonfUIKcneP';
    
        
        
                $access_token = $_SESSION["access_token"];
            
                $curl = curl_init();

                $timestamp = time() + 1;
                $time = date('H:i:s', $timestamp);

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.zoom.us/v2/users/me/meetings',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "agenda": "Collar APP Meeting",
                "default_password": false,
                "duration": 60,
                "password": "123456",
                "start_time": "'.date("Y-m-d").'T'.$time.'Z",
                "timezone": "Asia/Kolkata",
                "topic": "My meeting 3",
                "type": "2",
                "schedule_for": "zahoor.aviontech@gmail.com",
                "recurrence": {"type": 1,
                    "repeat_interval": 1
                    },
            "settings": {"host_video": "true",
                  "participant_video": "true",
                  "join_before_host": "False",
                  "mute_upon_entry": "False",
                  "watermark": "true",
                  "audio": "voip",
                  "auto_recording": "cloud"
                  }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer '.$access_token,
                    'Cookie: __cf_bm=_9hDTFNlHsA0f8JFHL1mNz7zz_Qo5fQqc2ROE1BSSWk-1710239541-1.0.1.1-pkh.6oFQ1vBeVQ4EEFgDqLLjYQ68WNmxgI78yfXCfTsoQaTuOxiUF4hUJGvdlgXQI4yrFtJdlEaLjb4STe1cJg; _zm_chtaid=528; _zm_ctaid=flvVhNrxTA2-bE-dN-Yrkg.1710239541844.381a90d14d132ffe193b522b63cf5bd1; _zm_currency=USD; _zm_mtk_guid=c5d7414538dc4dcf9f884ecedadc1311; _zm_page_auth=us05_c_PTq3xreHQLSfMtBJ_3bczw; _zm_ssid=us05_c_MIwluuFSQEWeKkhM-DOC2g; _zm_visitor_guid=c5d7414538dc4dcf9f884ecedadc1311; cred=93DB1C27FC9C21B8686053224B58AB89'
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                echo $response;
                exit();

                // $res = json_decode($response,true);
                
                // $x = '{  
                //         "join_url":"'.$res["join_url"].'",   
                //         "start_url":      56000
                //       }';

                // echo $x;   

                exit();
                // echo $res["join_url"];
                // die;
        
        
?>