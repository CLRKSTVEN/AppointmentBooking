<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>
<?php include('includes/ui_styles.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="page-title-box d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="page-title mb-0">Billing &amp; Payments</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-box">
                                <h5 class="mb-3">How to pay</h5>
                                <ol class="mb-0">
                                    <li>Book an appointment and proceed to the billing desk.</li>
                                    <li>Provide your appointment reference to settle payments.</li>
                                    <li>Keep your receipt for verification.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card-box">
                                <h5 class="mb-3">Payment history</h5>
                                <div class="text-muted small">No payments recorded yet.</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <?php include('includes/themecustomizer.php'); ?>
    <?php include('includes/footer_plugins.php'); ?>
</body>

</html>
