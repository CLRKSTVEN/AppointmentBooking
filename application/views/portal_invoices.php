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
                                    <h4 class="page-title mb-0">Invoices</h4>
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
                                <h5 class="mb-3">Create invoice</h5>
                                <form action="<?= site_url('portal/invoices'); ?>" method="post">
                                    <div class="form-group">
                                        <label for="user_id">Patient</label>
                                        <select id="user_id" name="user_id" class="form-control" required>
                                            <option value="">Select patient</option>
                                            <?php foreach (($clients ?? []) as $c): ?>
                                                <option value="<?= (int)$c['id']; ?>"><?= htmlentities($c['label']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" step="0.01" id="amount" name="amount" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Create invoice</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Invoices</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($invoices)): ?>
                                                <?php foreach ($invoices as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities($row->username ?? ''); ?></td>
                                                        <td><?= number_format((float)($row->amount ?? 0), 2); ?></td>
                                                        <td><span class="badge badge-<?= ($row->status ?? '') === 'paid' ? 'success' : 'secondary'; ?>">
                                                            <?= htmlentities($row->status ?? ''); ?>
                                                        </span></td>
                                                        <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-muted text-center">No invoices yet.</td></tr>
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
