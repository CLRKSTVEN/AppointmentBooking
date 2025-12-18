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
                                    <h4 class="page-title mb-0">Manage Staff</h4>
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
                                <h5 class="mb-3">Add staff</h5>
                                <form action="<?= site_url('portal/manage-staff'); ?>" method="post">
                                    <input type="hidden" name="action" value="create">
                                    <div class="form-group">
                                        <label for="first_name">First name</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last name</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="position_title">Position</label>
                                        <input type="text" id="position_title" name="position_title" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email (username)</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Create staff</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Staff accounts</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Position</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($staff)): ?>
                                                <?php foreach ($staff as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities(trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: '—'); ?></td>
                                                        <td><?= htmlentities($row->username ?? ''); ?></td>
                                                        <td><?= htmlentities($row->position_title ?? ''); ?></td>
                                                        <td>
                                                            <span class="badge badge-<?= (int)$row->status === 1 ? 'success' : 'secondary'; ?>">
                                                                <?= (int)$row->status === 1 ? 'Active' : 'Inactive'; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <form action="<?= site_url('portal/manage-staff'); ?>" method="post" class="d-inline">
                                                                <input type="hidden" name="action" value="toggle">
                                                                <input type="hidden" name="user_id" value="<?= (int)$row->id; ?>">
                                                                <input type="hidden" name="status" value="<?= (int)$row->status === 1 ? 0 : 1; ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                    <?= (int)$row->status === 1 ? 'Deactivate' : 'Activate'; ?>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-muted text-center">No staff found.</td>
                                                </tr>
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
