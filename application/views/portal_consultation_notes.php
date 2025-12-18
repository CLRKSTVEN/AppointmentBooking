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
                                    <h4 class="page-title mb-0">Consultation Notes</h4>
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
                                <h5 class="mb-3">Add note</h5>
                                <form action="<?= site_url('portal/consultation-notes'); ?>" method="post">
                                    <div class="form-group">
                                        <label for="user_id">Patient</label>
                                        <select id="user_id" name="user_id" class="form-control" required>
                                            <option value="">Select patient</option>
                                            <?php foreach (($clients ?? []) as $c): ?>
                                                <option value="<?= (int)$c['id']; ?>" <?= (!empty($selected_patient_id) && (int)$selected_patient_id === (int)$c['id']) ? 'selected' : ''; ?>>
                                                    <?= htmlentities($c['label']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea id="notes" name="notes" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Notes</h5>
                                    <form method="get" action="<?= site_url('portal/consultation-notes'); ?>" class="form-inline">
                                        <label class="mr-2" for="filter_patient">Filter by patient</label>
                                        <select id="filter_patient" name="patient_id" class="form-control form-control-sm mr-2">
                                            <option value="">All</option>
                                            <?php foreach (($clients ?? []) as $c): ?>
                                                <option value="<?= (int)$c['id']; ?>" <?= (!empty($selected_patient_id) && (int)$selected_patient_id === (int)$c['id']) ? 'selected' : ''; ?>>
                                                    <?= htmlentities($c['label']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Notes</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($notes)): ?>
                                                <?php foreach ($notes as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities(trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: ($row->username ?? '')); ?></td>
                                                        <td><?= nl2br(htmlentities($row->notes ?? '')); ?></td>
                                                        <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="3" class="text-muted text-center">No consultation notes yet.</td></tr>
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
