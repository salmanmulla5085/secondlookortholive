<?php 
$base_url = "http://174.141.231.46/~secondlookortho/zoom/get_access_token.php"; 

// $base_url = "http://localhost/zoom/index.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1zYkUFTEbW7gANk" crossorigin="anonymous">

    <style>
        /*service*/
        .services a {
            text-decoration: none;
        }
        .services .service-box {
            text-align: center;
        }
        .services .service-box h2 {
            color: #222;
            font-size: 20px;
            padding-top: 10px;
            text-decoration: none;
        }
        .services a .service-box:hover h2 {
            color: #FB0626;
        }
        .services .investor-box {
            background-color: #fdcc3b;
            background-position: center center;
            padding: 20px;
            width: 100%;
            min-height: 150px;
            display: block;
            position: relative;
            box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.2);
        }
        .services .investor-box h2 {
            font-size: 20px;
        }
        .services .investor-box .flip-view {
            position: absolute;
            top: 0;
            width: 100%;
            background-color: #212d70;
            left: -10%;
            padding: 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: all ease-in-out 333ms;
        }
        .services .investor-box a {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
        }
        .services .investor-box:hover .flip-view {
            left: 0;
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
<?php
session_start();
    $data = [];
    //  member_permission();
    $Client_ID = 'AnLNQvlvSCSM7UrvjdrNHQ';
    $Client_Secret = 'Wo17YvH1XP2BTT90RLYMrGonfUIKcneP';
    
        $code =  $this->request->getVar('code');
    
        // print_r($this->request->getVar());
        // echo "code : ".$this->request->getVar('code');
        // echo "<br>";-
        // echo "<br>";
            
        if(!empty($code))
        {
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://zoom.us/oauth/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'code='.$code.'&grant_type=authorization_code&redirect_uri='.$base_url,
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic '.base64_encode($Client_ID.':'.$Client_Secret)                
                ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response,true);
                
                // print_r($response);

                if(!empty($response["access_token"]))
                {
                    // echo $response["access_token"];

                    $_SESSION["access_token"] = $response["access_token"];
                    // echo "access_token : ".$response["access_token"];
                    // echo "<br>";
                    // echo "<br>";          
                }   
                else
                {
                }

    }
    
    $data["meetings"] = "";
    if(!empty($_SESSION["access_token"]))
    {

    echo $_SESSION["access_token"];
    $curl = curl_init();
            
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.zoom.us/v2/users/me/meetings?type=scheduled&page_size=30&page_number=1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Bearer '.$_SESSION["access_token"],
    ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $response = json_decode($response,true);
    
    // print_r($response);

    $data["meetings"] = $response;
    }
    // die;

    
?>
<main class="page-container container">
    <div class="page-contents">
        <div class="right-spacing position-relative">
            <div class="row">                

                <!-- Create New Meeting Section -->
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="page-titles mb-3">
                        <h4>Create New Meeting</h4>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-end align-items-end">
                                <a href="/zoom/create_meeting.php">Click here to Create New Meeting</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="col-12">
                    <div class="page-titles mb-3"></div>

                    <div class="services pb-5">
                        <div class="container">
                            <div class="pt-5">
                                <h2 class="vc_custom_heading ico_header">
                                    Zoom Meetings
                                </h2>
                                <hr>
                                <div class="row">
                                    <?php 
                                    if (!empty($meetings["meetings2"])) {
                                        foreach ($meetings["meetings"] as $k => $meet) { ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="investor-box">
                                                    <h2><?= ++$k ?> - <?= $meet["topic"] ?></h2><br>
                                                    Start Time: <?= $meet["start_time"] ?><br>
                                                    Time Zone: <?= $meet["timezone"] ?><br>
                                                    Created at: <?= $meet["created_at"] ?><br>
                                                    <div class="flip-view">
                                                        <a target="_blank" href="<?= $meet["join_url"] ?>">
                                                            Join Meeting &nbsp;<i class="fas fa-chevron-circle-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php 
                                        }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Services Section -->

                </div>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap JS and Popper.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93F13Z26ZZ6bq0dAwFxgiA3oP5AwBtNhaXpI2IgyxL3O5pC5VBzVfZC5K87AKG" crossorigin="anonymous"></script>

</body>
</html>
