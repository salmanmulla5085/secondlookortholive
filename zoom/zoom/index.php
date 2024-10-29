<?= $this->extend('layouts/dashboard_layout') ?>
<?= $this->section('title') ?>
Home Page
<?= $this->endSection() ?>
<?= $this->section('content') ?>

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
<main class="page-container">
    <div class="page-contents">
        <div class="right-spacing position-relative">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="page-titles mb-3">
                        <h4>Configure Zoom</h4>
                    </div>
                    <div class="card-main mb-3">
                        <div class="card-main-body">
                            <div class="d-flex justify-content-end align-items-end">
                                <a href="https://zoom.us/oauth/authorize?response_type=code&client_id=FmS5sgWsSbWLIPTlL6OOdg&redirect_uri=<?= base_url("zoom/index")?>">Connect & Authorize Zoom</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="page-titles mb-3">
                        <h4>Create New Meeting</h4>
                    </div>
                    <div class="card-main mb-3">
                        <div class="card-main-body">
                            <div class="d-flex justify-content-end align-items-end">
                                <a href="<?=base_url("zoom/create_meeting")?>">Click here to Create New Meeting</a>
                            </div>
                        </div>
                    </div>
                </div>
                 -->
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="page-titles mb-3">
                    
                    </div>

                    <!--services-->
        <div class="services pb-5">
          <div class="container">
            <div class="pt-5">
              <h2 class="vc_custom_heading ico_header">
              Zoom Meetings
              </h2>
              <hr>
              <div class="row">
              <?php 
                            if(!empty($meetings["meetings2"]))
                            {
                            foreach($meetings["meetings"] as $k=>$meet)
                            {
                            ?>
                                <div class="col-md-6 mb-3">
                                  <div class="investor-box">
                                    <h2><?= ++$k ?> - <?= $meet["topic"]?></h2><br> 
                                                    Start Time: <?= $meet["start_time"]?> <br> 
                                                    Time Zone: <?= $meet["timezone"]?> <br> 
                                                    Created at: <?= $meet["created_at"]?> <br> 
                                                    <!-- Join URL:<?= $meet["join_url"]?> <br>  -->
                                    <div class="flip-view">
                                      <a target="_blank" href="<?= $meet["join_url"]?>">Join Meeting &nbsp;<i class="fas fa-chevron-circle-right"></i></a>
                                    </div>
                                  </div>
                                </div>

                            <?php 
                            }
                            }?>
             
              </div>
            </div>
          </div>
        </div>
        <!--end services-->

                    
                           
                            
                        
                </div>

                
            </div>
        </div>
    </div>
    <?= $this->endSection() ?>
</main>