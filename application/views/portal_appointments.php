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
                                    <h4 class="page-title mb-0"><?= htmlentities($heading ?? 'My Appointments'); ?></h4>
                                </div>
                                <?php if (($mode ?? '') === 'client'): ?>
                                    <a href="<?= site_url('dashboard/log'); ?>" class="btn btn-primary btn-sm">Book appointment</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-box mt-2">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Date/Time</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <?php if (($mode ?? '') === 'client' || ($mode ?? '') === 'staff_manage'): ?>
                                            <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($appointments)): ?>
                                        <?php foreach ($appointments as $row): ?>
                                            <?php
                                            $startRaw = $row->start_date ?? '';
                                            $startFormatted = $startRaw && strtotime($startRaw) ? date('M d, Y h:i a', strtotime($startRaw)) : '—';
                                            ?>
                                            <tr>
                                                <td><?= htmlentities($row->title ?? 'Untitled'); ?></td>
                                                <td><?= htmlentities($row->category ?? ''); ?></td>
                                                <td><?= htmlentities($startFormatted); ?></td>
                                                <td><?= htmlentities($row->location ?? ''); ?></td>
                                                <td><span class="badge badge-<?= ($row->status ?? 'pending') === 'accepted' ? 'success' : (($row->status ?? 'pending') === 'declined' ? 'danger' : 'secondary'); ?>">
                                                    <?= htmlentities($row->status ?? 'pending'); ?>
                                                </span></td>
                                                <?php if (($mode ?? '') === 'client'): ?>
                                                    <td>
                                                        <a href="<?= site_url('portal/reschedule/' . (int)$row->id); ?>" class="btn btn-outline-primary btn-sm">Reschedule</a>
                                                        <a href="<?= site_url('portal/cancel/' . (int)$row->id); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Cancel this appointment?');">Cancel</a>
                                                    </td>
                                                <?php elseif (($mode ?? '') === 'staff_manage'): ?>
                                                    <td>
                                                        <form action="<?= site_url('dashboard/log/status'); ?>" method="post" class="d-inline">
                                                            <input type="hidden" name="id" value="<?= (int)$row->id; ?>">
                                                            <input type="hidden" name="status" value="accepted">
                                                            <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                                        </form>
                                                        <form action="<?= site_url('dashboard/log/status'); ?>" method="post" class="d-inline">
                                                            <input type="hidden" name="id" value="<?= (int)$row->id; ?>">
                                                            <input type="hidden" name="status" value="declined">
                                                            <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                                        </form>
                                                        <form action="<?= site_url('dashboard/log/status'); ?>" method="post" class="d-inline">
                                                            <input type="hidden" name="id" value="<?= (int)$row->id; ?>">
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-outline-secondary btn-sm">Complete</button>
                                                        </form>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-muted text-center">No appointments yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
