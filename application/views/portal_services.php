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
                                    <h4 class="page-title mb-0">Medical Services</h4>
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
                                <h5 class="mb-3">Add service</h5>
                                <form action="<?= site_url('portal/services'); ?>" method="post">
                                    <input type="hidden" name="action" value="add">
                                    <div class="form-group">
                                        <label for="name">Service name</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" step="0.01" id="price" name="price" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="card-box">
                                <h5 class="mb-3">Services</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($services)): ?>
                                                <?php foreach ($services as $svc): ?>
                                                    <tr>
                                                        <td><?= htmlentities($svc->name ?? ''); ?></td>
                                                        <td><?= number_format((float)($svc->price ?? 0), 2); ?></td>
                                                        <td><span class="badge badge-<?= (int)($svc->is_active ?? 1) === 1 ? 'success' : 'secondary'; ?>">
                                                            <?= (int)($svc->is_active ?? 1) === 1 ? 'Active' : 'Inactive'; ?>
                                                        </span></td>
                                                        <td>
                                                            <form action="<?= site_url('portal/services'); ?>" method="post" class="d-inline">
                                                                <input type="hidden" name="action" value="toggle">
                                                                <input type="hidden" name="id" value="<?= (int)$svc->id; ?>">
                                                                <input type="hidden" name="status" value="<?= (int)$svc->is_active === 1 ? 0 : 1; ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                    <?= (int)$svc->is_active === 1 ? 'Deactivate' : 'Activate'; ?>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-muted text-center">No services yet.</td></tr>
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
