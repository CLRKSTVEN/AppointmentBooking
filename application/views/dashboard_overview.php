<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Online Appointment Booking — My Dashboard</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-people float-right"></i>
                                <h6 class="text-muted text-uppercase mt-0">Total staff</h6>
                                <h3 class="my-1"><?= (int)($stats['total_staff'] ?? 0); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-building float-right"></i>
                                <h6 class="text-muted text-uppercase mt-0">Offices</h6>
                                <h3 class="my-1"><?= (int)($stats['total_offices'] ?? 0); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-calendar-check float-right"></i>
                                <h6 class="text-muted text-uppercase mt-0">My appointments</h6>
                                <h3 class="my-1"><?= (int)($stats['total_accomplishments'] ?? 0); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-unlock float-right"></i>
                                <h6 class="text-muted text-uppercase mt-0">Public appointments</h6>
                                <h3 class="my-1"><?= (int)($stats['public_accomplishments'] ?? 0); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Recent appointments</h5>
                                    <a href="<?= site_url('dashboard/log'); ?>" class="btn btn-sm btn-primary">Log an appointment</a>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($accomplishments)): ?>
                                        <?php foreach ($accomplishments as $row): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlentities($row->title ?? 'Untitled'); ?></strong>
                                                <div class="text-muted small">
                                                    <?= htmlentities($row->category ?? 'General'); ?>
                                                    <?php if (!empty($row->start_date)): ?> · <?= htmlentities($row->start_date); ?><?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-muted"><?= $has_staff_profile ? 'No appointments yet. Log one below.' : 'No staff profile found. Ask an admin to register you.'; ?></li>
                                    <?php endif; ?>
                                </ul>
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