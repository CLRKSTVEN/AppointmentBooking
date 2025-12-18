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
                                    <h4 class="page-title mb-0">Doctors &amp; Schedules</h4>
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

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="card-box">
                                <h5 class="mb-3">Add doctor</h5>
                                <form action="<?= site_url('portal/doctors'); ?>" method="post">
                                    <input type="hidden" name="action" value="add">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="specialty">Specialty</label>
                                        <input type="text" id="specialty" name="specialty" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="card-box">
                                <h5 class="mb-3">Doctors</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Specialty</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($doctors)): ?>
                                                <?php foreach ($doctors as $doc): ?>
                                                    <tr>
                                                        <td><?= htmlentities($doc->name ?? ''); ?></td>
                                                        <td><?= htmlentities($doc->specialty ?? ''); ?></td>
                                                        <td><span class="badge badge-<?= (int)($doc->is_active ?? 1) === 1 ? 'success' : 'secondary'; ?>">
                                                            <?= (int)($doc->is_active ?? 1) === 1 ? 'Active' : 'Inactive'; ?>
                                                        </span></td>
                                                        <td>
                                                            <form action="<?= site_url('portal/doctors'); ?>" method="post" class="d-inline">
                                                                <input type="hidden" name="action" value="toggle">
                                                                <input type="hidden" name="id" value="<?= (int)$doc->id; ?>">
                                                                <input type="hidden" name="status" value="<?= (int)$doc->is_active === 1 ? 0 : 1; ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                    <?= (int)$doc->is_active === 1 ? 'Deactivate' : 'Activate'; ?>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-muted text-center">No doctors yet.</td></tr>
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
