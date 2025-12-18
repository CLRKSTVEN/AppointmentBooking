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
                                    <h4 class="page-title mb-0">Prescriptions</h4>
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
                        <?php if (empty($client_view)): ?>
                            <div class="col-lg-4">
                                <div class="card-box">
                                    <h5 class="mb-3">Add prescription</h5>
                                    <form action="<?= site_url('portal/prescriptions'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="user_id">Patient</label>
                                            <select id="user_id" name="user_id" class="form-control" required>
                                                <option value="">Select patient</option>
                                                <?php foreach ($clients as $c): ?>
                                                    <option value="<?= (int)$c['id']; ?>"><?= htmlentities($c['label']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="medication">Medication</label>
                                            <input type="text" id="medication" name="medication" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="dosage">Dosage</label>
                                            <input type="text" id="dosage" name="dosage" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="instructions">Instructions</label>
                                            <textarea id="instructions" name="instructions" class="form-control" rows="3"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="<?= empty($client_view) ? 'col-lg-8' : 'col-lg-12'; ?>">
                            <div class="card-box">
                                <h5 class="mb-3"><?= empty($client_view) ? 'Recent prescriptions' : 'My prescriptions'; ?></h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <?php if (empty($client_view)): ?>
                                                    <th>Patient</th>
                                                <?php endif; ?>
                                                <th>Medication</th>
                                                <th>Dosage</th>
                                                <th>Instructions</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($prescriptions)): ?>
                                                <?php foreach ($prescriptions as $row): ?>
                                                    <tr>
                                                        <?php if (empty($client_view)): ?>
                                                            <td><?= htmlentities($row->patient_name ?? ''); ?></td>
                                                        <?php endif; ?>
                                                        <td><?= htmlentities($row->medication ?? ''); ?></td>
                                                        <td><?= htmlentities($row->dosage ?? ''); ?></td>
                                                        <td><?= htmlentities($row->instructions ?? ''); ?></td>
                                                        <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="<?= empty($client_view) ? '5' : '4'; ?>" class="text-muted text-center">No prescriptions yet.</td>
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
