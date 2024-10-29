<?= $this->extend('layouts/dashboard_layout') ?>
<?= $this->section('title') ?>
Home Page
<?= $this->endSection() ?>
<?= $this->section('content') ?>
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
                                <a href="https://zoom.us/oauth/authorize?response_type=code&client_id=FmS5sgWsSbWLIPTlL6OOdg&redirect_uri=http://174.141.233.253/~holdspacesocial/website/zoom/index">Connect & Authorize Zoom</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection() ?>
</main>