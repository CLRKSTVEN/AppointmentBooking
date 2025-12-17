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
                            <div class="page-title-box d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="page-title mb-0">Online Appointment Booking — My Dashboard</h4>
                                    <span class="badge badge-<?= !empty($is_admin) ? 'danger' : (!empty($is_staff) ? 'info' : 'primary'); ?>">
                                        <?= !empty($is_admin) ? 'Admin' : (!empty($is_staff) ? 'Staff' : 'Client'); ?>
                                    </span>
                                </div>
                                <span id="ph-time-api" class="ml-3 font-weight-bold text-primary" style="font-size:1.1em;"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if (!empty($is_staff) || !empty($is_admin)): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-collection float-right"></i>
                                    <h6 class="text-muted text-uppercase mt-0">Total appointments</h6>
                                    <h3 class="my-1"><?= (int)($stats['total_appointments'] ?? 0); ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-hourglass-split float-right"></i>
                                    <h6 class="text-muted text-uppercase mt-0">Pending</h6>
                                    <h3 class="my-1"><?= (int)($stats['pending'] ?? 0); ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-check2-circle float-right"></i>
                                    <h6 class="text-muted text-uppercase mt-0">Accepted</h6>
                                    <h3 class="my-1"><?= (int)($stats['accepted'] ?? 0); ?></h3>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-calendar-check float-right"></i>
                                    <h6 class="text-muted text-uppercase mt-0">My appointments</h6>
                                    <h3 class="my-1"><?= (int)($stats['total_accomplishments'] ?? 0); ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-unlock float-right"></i>
                                    <h6 class="text-muted text-uppercase mt-0">Public appointments</h6>
                                    <h3 class="my-1"><?= (int)($stats['public_accomplishments'] ?? 0); ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-clock-history float-right"></i>
                                    <h6 class="text-muted text-uppercase mt-0">Latest update</h6>
                                    <h3 class="my-1"><?= !empty($accomplishments) ? htmlentities($accomplishments[0]->start_date ?? '—') : '—'; ?></h3>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Recent appointments</h5>
                                    <?php if (!empty($is_client)): ?>
                                        <a href="<?= site_url('dashboard/log'); ?>" class="btn btn-sm btn-primary">Log an appointment</a>
                                    <?php endif; ?>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($accomplishments)): ?>
                                        <?php foreach ($accomplishments as $row): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlentities($row->title ?? 'Untitled'); ?></strong>
                                                <div class="text-muted small">
                                                    <?= htmlentities($row->category ?? 'General'); ?>
                                                    <?php if (!empty($row->start_date)): ?> · <?= htmlentities($row->start_date); ?><?php endif; ?>
                                                    <?php if (!empty($is_staff) || !empty($is_admin)): ?>
                                                        <?php $by = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')); ?>
                                                        <?= $by !== '' ? ' · by ' . htmlentities($by) : ''; ?>
                                                        <?= !empty($row->status) ? ' · ' . htmlentities($row->status) : ''; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-muted">
                                            <?= $has_staff_profile ? 'No appointments yet.' : 'No staff profile found. Ask an admin to register you.'; ?>
                                        </li>
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
