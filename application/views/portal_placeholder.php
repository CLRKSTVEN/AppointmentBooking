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
                                    <h4 class="page-title mb-0"><?= htmlentities($title ?? 'Coming soon'); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-box mt-2">
                        <p><?= htmlentities($description ?? 'We are preparing this feature.'); ?></p>
                        <?php if (!empty($actions)): ?>
                            <div class="mt-3">
                                <?php foreach ($actions as $action): ?>
                                    <a href="<?= $action['href']; ?>" class="btn btn-outline-primary btn-sm mr-2"><?= htmlentities($action['label']); ?></a>
                                <?php endforeach; ?>
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
