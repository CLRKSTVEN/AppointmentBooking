<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid admin-dashboard-wrap">

                    <style>
                        .admin-dashboard-wrap .row {
                            margin-bottom: 16px;
                        }

                        .admin-titlebar {
                            align-items: flex-start;
                            gap: 12px;
                        }

                        .admin-title-left {
                            display: flex;
                            flex-direction: column;
                            align-items: flex-start;
                        }

                        .admin-title-left .page-title {
                            line-height: 1.2;
                        }

                        .admin-time {
                            font-size: 1.1em;
                            line-height: 1.2;
                            margin-top: 2px;
                            white-space: nowrap;
                        }

                        .tilebox-one {
                            height: 100%;
                            display: flex;
                            flex-direction: column;
                            justify-content: space-between;
                        }

                        .tilebox-one i {
                            font-size: 1.5rem;
                            opacity: .85;
                        }

                        .card-box {
                            height: 100%;
                        }
                    </style>

                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="page-title-box admin-titlebar d-flex justify-content-between">
                                <div class="admin-title-left">
                                    <h4 class="page-title mb-0">AppointFlow — Admin Dashboard</h4>
                                </div>
                                <span id="ph-time-api" class="ml-3 font-weight-bold text-primary admin-time"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex align-items-stretch">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-people float-right"></i>
                                <div>
                                    <h6 class="text-muted text-uppercase mt-0">Total staff</h6>
                                    <h3 class="my-1"><?= (int)($stats['total_staff'] ?? 0); ?></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-building float-right"></i>
                                <div>
                                    <h6 class="text-muted text-uppercase mt-0">Offices</h6>
                                    <h3 class="my-1"><?= (int)($stats['total_offices'] ?? 0); ?></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-calendar-check float-right"></i>
                                <div>
                                    <h6 class="text-muted text-uppercase mt-0">Appointments logged</h6>
                                    <h3 class="my-1"><?= (int)($stats['total_accomplishments'] ?? 0); ?></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card-box tilebox-one">
                                <i class="bi bi-person-check float-right"></i>
                                <div>
                                    <h6 class="text-muted text-uppercase mt-0">My appointments</h6>
                                    <h3 class="my-1"><?= (int)($stats['my_accomplishments'] ?? 0); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex align-items-stretch">
                        <div class="col-lg-6 mb-3">
                            <div class="card-box h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                    <h5 class="mb-0">Latest staff</h5>
                                    <a href="<?= site_url('register'); ?>" class="btn btn-sm btn-primary mt-2 mt-sm-0">Register staff</a>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($latest_staff)): ?>
                                        <?php foreach ($latest_staff as $row): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlentities(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')); ?></strong>
                                                <div class="text-muted small"><?= htmlentities($row->position_title ?? ''); ?></div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-muted">No staff added yet.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card-box h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                    <h5 class="mb-0">Recent appointments</h5>
                                    <a href="<?= site_url('dashboard/log'); ?>" class="btn btn-sm btn-outline-primary mt-2 mt-sm-0">View all</a>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($recent_accomplishments)): ?>
                                        <?php foreach ($recent_accomplishments as $row): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlentities($row->title ?? 'Untitled'); ?></strong>
                                                <div class="text-muted small">
                                                    <?= htmlentities($row->category ?? 'General'); ?>
                                                    <?php if (!empty($row->start_date)): ?> · <?= htmlentities($row->start_date); ?><?php endif; ?>
                                                        <?php $by = ($row->first_name ?? '') . ' ' . ($row->last_name ?? ''); ?>
                                                        <?php if ($by !== ''): ?> · by <?= htmlentities($by); ?><?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-muted">No appointments logged yet.</li>
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
