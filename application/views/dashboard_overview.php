<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid dashboard-wrap">

                    <style>
                        .dashboard-wrap .row {
                            margin-bottom: 16px;
                        }

                        .dashboard-titlebar {
                            align-items: flex-start;
                            gap: 12px;
                        }

                        .title-stack {
                            display: flex;
                            flex-direction: column;
                            align-items: flex-start;
                        }

                        .title-stack .page-title {
                            line-height: 1.2;
                        }

                        .title-stack .subline {
                            margin-top: 6px;
                        }

                        .ph-time {
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
                            <div class="page-title-box dashboard-titlebar d-flex justify-content-between">
                                <div class="title-stack">
                                    <h4 class="page-title mb-1">AppointFlow — My Dashboard</h4>
                                    <div class="subline">
                                        <span class="badge badge-<?= !empty($is_admin) ? 'danger' : (!empty($is_staff) ? 'info' : 'primary'); ?>">
                                            <?= !empty($is_admin) ? 'Admin' : (!empty($is_staff) ? 'Staff' : 'Client'); ?>
                                        </span>
                                    </div>
                                </div>
                                <span id="ph-time-api" class="ml-3 font-weight-bold text-primary ph-time"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex align-items-stretch">
                        <?php if (!empty($is_staff) || !empty($is_admin)): ?>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-collection float-right"></i>
                                    <div>
                                        <h6 class="text-muted text-uppercase mt-0">Total appointments</h6>
                                        <h3 class="my-1"><?= (int)($stats['total_appointments'] ?? 0); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-hourglass-split float-right"></i>
                                    <div>
                                        <h6 class="text-muted text-uppercase mt-0">Pending</h6>
                                        <h3 class="my-1"><?= (int)($stats['pending'] ?? 0); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-check2-circle float-right"></i>
                                    <div>
                                        <h6 class="text-muted text-uppercase mt-0">Accepted</h6>
                                        <h3 class="my-1"><?= (int)($stats['accepted'] ?? 0); ?></h3>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-calendar-check float-right"></i>
                                    <div>
                                        <h6 class="text-muted text-uppercase mt-0">My appointments</h6>
                                        <h3 class="my-1"><?= (int)($stats['total_accomplishments'] ?? 0); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-unlock float-right"></i>
                                    <div>
                                        <h6 class="text-muted text-uppercase mt-0">Public appointments</h6>
                                        <h3 class="my-1"><?= (int)($stats['public_accomplishments'] ?? 0); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="card-box tilebox-one">
                                    <i class="bi bi-clock-history float-right"></i>
                                    <div>
                                        <h6 class="text-muted text-uppercase mt-0">Latest update</h6>
                                        <h3 class="my-1"><?= !empty($accomplishments) ? htmlentities($accomplishments[0]->start_date ?? '—') : '—'; ?></h3>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row d-flex align-items-stretch">
                        <div class="col-lg-8 mb-3">
                            <div class="card-box h-100">
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
                                                            <?php $by = ($row->first_name ?? '') . ' ' . ($row->last_name ?? ''); ?>
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

                        <div class="col-lg-4 mb-3">
                            <div class="card-box h-100">
                                <h5 class="mb-2">Quick Summary</h5>

                                <div class="text-muted small mb-2">
                                    Currently login to <b>SY <?= $this->session->userdata('sy'); ?> <?= $this->session->userdata('semester'); ?></b>
                                </div>

                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total</span>
                                        <b><?= (int)($stats['total_appointments'] ?? $stats['total_accomplishments'] ?? 0); ?></b>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Pending</span>
                                        <b><?= (int)($stats['pending'] ?? 0); ?></b>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Accepted</span>
                                        <b><?= (int)($stats['accepted'] ?? 0); ?></b>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Latest</span>
                                        <b><?= !empty($accomplishments) ? htmlentities($accomplishments[0]->start_date ?? '—') : '—'; ?></b>
                                    </div>
                                </div>
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
