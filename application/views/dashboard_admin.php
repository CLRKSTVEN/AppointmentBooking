<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<?php
$fullName = $this->session->userdata('full_name') ?: $this->session->userdata('username');
$roleLabel = 'Admin';
$stats = isset($stats) ? (array) $stats : [];
$dashboardNav = !empty($dashboard_nav) ? $dashboard_nav : [];
$recentAccomplishments = isset($recent_accomplishments) ? $recent_accomplishments : [];
$latestStaff = isset($latest_staff) ? $latest_staff : [];
?>

<style>
    .overview-header {
        border-radius: 18px;
        padding: 18px 24px;
        background: linear-gradient(135deg, #0ea5e9, #22c55e);
        color: #fff;
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.25);
    }

    .overview-header h4 {
        margin-bottom: 4px;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .overview-header small {
        opacity: 0.85;
    }

    .overview-actions .btn {
        min-width: 170px;
        border-radius: 999px;
    }

    .stat-card {
        border-radius: 18px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: none;
    }

    .stat-card .stat-label {
        font-size: 0.78rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 6px;
    }

    .stat-card .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        color: #0f172a;
    }

    .activity-card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .activity-card h5 {
        margin-bottom: 0.35rem;
    }

    .activity-item {
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .activity-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .pill {
        display: inline-block;
        padding: 3px 10px;
        font-size: 0.75rem;
        border-radius: 999px;
        background: #e0f2fe;
        color: #0369a1;
    }
</style>

<body>

    <div id="wrapper">

        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title mb-0">Admin dashboard</h4>
                                <div class="page-title-right text-muted small">
                                    <?= html_escape($fullName); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="overview-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <h4 class="mb-0">Office Tracker control center</h4>
                                    <small>Monitor staff activity and jump into key actions.</small>
                                </div>
                                <div class="overview-actions d-flex flex-wrap gap-2">
                                    <?php if (!empty($dashboardNav)): ?>
                                        <?php foreach ($dashboardNav as $nav): ?>
                                            <a class="btn btn-outline-light btn-sm" href="<?= html_escape($nav['target']); ?>">
                                                <?= html_escape($nav['label']); ?>
                                                <?php if (!empty($nav['count'])): ?>
                                                    <span class="badge badge-light ml-1"><?= (int) $nav['count']; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <a class="btn btn-outline-light btn-sm" href="<?= site_url('dashboard'); ?>">Refresh</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $statCards = [
                        ['label' => 'Active staff', 'value' => $stats['total_staff'] ?? 0, 'meta' => 'Rostered personnel'],
                        ['label' => 'Offices', 'value' => $stats['total_offices'] ?? 0, 'meta' => 'Managed locations'],
                        ['label' => 'Total accomplishments', 'value' => $stats['total_accomplishments'] ?? 0, 'meta' => 'All staff'],
                        ['label' => 'My public posts', 'value' => $stats['public_accomplishments'] ?? 0, 'meta' => 'Visible to visitors'],
                    ];
                    ?>
                    <section class="stat-grid mt-3">
                        <div class="row">
                            <?php foreach ($statCards as $card): ?>
                                <div class="col-md-6 col-xl-3 mb-3">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <div class="stat-label"><?= html_escape($card['label']); ?></div>
                                            <div class="stat-value"><?= number_format((int) $card['value']); ?></div>
                                            <div class="text-muted">
                                                <?= html_escape($card['meta']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section class="mt-3">
                        <div class="row">
                            <div class="col-xl-7 mb-3">
                                <div class="card activity-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <h5 class="mb-1">Recent accomplishments</h5>
                                                <small class="text-muted">Latest updates across the team.</small>
                                            </div>
                                            <a class="pill" href="<?= site_url('dashboard/log'); ?>">Log new</a>
                                        </div>

                                        <?php if (!empty($recentAccomplishments)): ?>
                                            <?php foreach ($recentAccomplishments as $item): ?>
                                                <?php
                                                $name = trim(($item->first_name ?? '') . ' ' . ($item->last_name ?? ''));
                                                $staffName = $name !== '' ? $name : 'Unassigned';
                                                $range = ($item->start_date ?: 'TBD') . ' → ' . ($item->end_date ?: 'Ongoing');
                                                ?>
                                                <div class="activity-item">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <div class="font-weight-semibold"><?= html_escape($item->title); ?></div>
                                                            <div class="text-muted small">
                                                                <?= html_escape($staffName); ?>
                                                                <?php if (!empty($item->position_title)): ?>
                                                                    · <?= html_escape($item->position_title); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="text-muted small"><?= html_escape($range); ?></div>
                                                        </div>
                                                        <span class="pill"><?= $item->is_public ? 'Public' : 'Private'; ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">No recent accomplishments yet.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-5 mb-3">
                                <div class="card activity-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <h5 class="mb-1">Latest staff</h5>
                                                <small class="text-muted">New and recently updated profiles.</small>
                                            </div>
                                            <a class="pill" href="<?= site_url('register'); ?>">Add staff</a>
                                        </div>

                                        <?php if (!empty($latestStaff)): ?>
                                            <?php foreach ($latestStaff as $staff): ?>
                                                <?php
                                                $name = trim(($staff->first_name ?? '') . ' ' . ($staff->last_name ?? ''));
                                                ?>
                                                <div class="activity-item">
                                                    <div class="font-weight-semibold"><?= html_escape($name); ?></div>
                                                    <div class="text-muted small">
                                                        <?= html_escape($staff->position_title ?? 'No position set'); ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">No staff records found.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>

        <?php include('includes/themecustomizer.php'); ?>

    </div>

    <?php include('includes/footer_plugins.php'); ?>

</body>

</html>
