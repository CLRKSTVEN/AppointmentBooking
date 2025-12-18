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
                                    <h4 class="page-title mb-0">Booking Rules &amp; Limits</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlentities($this->session->flashdata('success')); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlentities($this->session->flashdata('error')); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-box">
                                <h5 class="mb-3">Add rule</h5>
                                <form action="<?= site_url('portal/booking-rules'); ?>" method="post">
                                    <div class="form-group">
                                        <label for="name">Rule name</label>
                                        <input type="text" id="name" name="name" class="form-control" required placeholder="Max per day">
                                    </div>
                                    <div class="form-group">
                                        <label for="value">Value</label>
                                        <input type="text" id="value" name="value" class="form-control" required placeholder="e.g., 10">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save rule</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Rules</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Value</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($rules)): ?>
                                                <?php foreach ($rules as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities($row->name ?? ''); ?></td>
                                                        <td><?= htmlentities($row->value ?? ''); ?></td>
                                                        <td><span class="badge badge-<?= (int)($row->is_active ?? 1) === 1 ? 'success' : 'secondary'; ?>">
                                                            <?= (int)($row->is_active ?? 1) === 1 ? 'Active' : 'Inactive'; ?>
                                                        </span></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="3" class="text-muted text-center">No rules yet.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
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
