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
                                    <h4 class="page-title mb-0"><?= htmlentities($heading ?? 'Reports'); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach (($stats ?? []) as $label => $value): ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card-box text-center">
                                    <h5 class="text-muted text-uppercase mt-0"><?= htmlentities(str_replace('_', ' ', $label)); ?></h5>
                                    <h3 class="my-2"><?= (int)$value; ?></h3>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($stats)): ?>
                            <div class="col-12">
                                <div class="card-box text-muted">No data available.</div>
                            </div>
                        <?php endif; ?>
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
