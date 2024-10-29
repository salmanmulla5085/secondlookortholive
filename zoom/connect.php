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

<main class="page-container container">
    <div class="page-contents">
        <div class="right-spacing position-relative">
            <div class="row">
                <div class="col-12">
                    <div class="page-titles mb-3">
                        <h4>Configure Zoom</h4>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-end align-items-end">
                                <?php
                                        $client_id = 'AnLNQvlvSCSM7UrvjdrNHQ';
                                        $redirect_uri = urlencode($base_url); // Ensure redirect_uri is encoded

                                        
                                        $scopes = urlencode('meeting:write user:read');

                                        // Generate the authorization URL
                                        $auth_url = "https://zoom.us/oauth/authorize?response_type=code&client_id={$client_id}&scope={$scopes}&redirect_uri={$redirect_uri}";

                                         ?>
                                <a href="<?= $auth_url?>">
                                    Connect & Authorize Zoom
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create New Meeting Section -->
                
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
