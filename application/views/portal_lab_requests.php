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
                                    <h4 class="page-title mb-0">Lab Requests / Results</h4>
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
                                <h5 class="mb-3">Add lab request</h5>
                                <form action="<?= site_url('portal/lab-requests'); ?>" method="post">
                                    <div class="form-group">
                                        <label for="patient_name">Patient name</label>
                                        <input type="text" id="patient_name" name="patient_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="test_name">Test</label>
                                        <input type="text" id="test_name" name="test_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-control">
                                            <option value="requested">Requested</option>
                                            <option value="in_progress">In progress</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="result">Result (optional)</label>
                                        <textarea id="result" name="result" class="form-control" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Lab requests</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Test</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($requests)): ?>
                                                <?php foreach ($requests as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities($row->patient_name ?? ''); ?></td>
                                                        <td><?= htmlentities($row->test_name ?? ''); ?></td>
                                                        <td><?= htmlentities($row->status ?? ''); ?></td>
                                                        <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-muted text-center">No lab requests yet.</td></tr>
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
