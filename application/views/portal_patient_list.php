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
                                    <h4 class="page-title mb-0">Patient List</h4>
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
                                <h5 class="mb-3">Add patient</h5>
                                <form action="<?= site_url('portal/patient-list'); ?>" method="post">
                                    <div class="form-group">
                                        <label for="first_name">First name</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last name</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email (username)</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Create patient</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Patients</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($patients)): ?>
                                                <?php foreach ($patients as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities(trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: '—'); ?></td>
                                                        <td><?= htmlentities($row->username ?? ''); ?></td>
                                                        <td>
                                                            <span class="badge badge-<?= (int)($row->status ?? 0) === 1 ? 'success' : 'secondary'; ?>">
                                                                <?= (int)($row->status ?? 0) === 1 ? 'Active' : 'Inactive'; ?>
                                                            </span>
                                                        </td>
                                                        <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                                        <td>
                                                            <a class="btn btn-sm btn-outline-primary" href="<?= site_url('portal/medical-records?patient_id=' . (int)$row->id); ?>">Medical records</a>
                                                            <a class="btn btn-sm btn-outline-info" href="<?= site_url('portal/consultation-notes?patient_id=' . (int)$row->id); ?>">Consultation notes</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-muted text-center">No patients found.</td></tr>
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
