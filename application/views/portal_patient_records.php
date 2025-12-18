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
                                    <h4 class="page-title mb-0">My Medical Records</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <?php if (!empty($records)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Notes</th>
                                            <th>Recorded By</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($records as $row): ?>
                                            <?php
                                            $by = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
                                            ?>
                                            <tr>
                                                <td><?= htmlentities($row->title ?? ''); ?></td>
                                                <td><?= nl2br(htmlentities($row->notes ?? '')); ?></td>
                                                <td><?= htmlentities($by !== '' ? $by : 'Staff'); ?></td>
                                                <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-muted">No medical records yet.</div>
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
