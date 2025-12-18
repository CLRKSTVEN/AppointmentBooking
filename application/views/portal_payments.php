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
                                    <h4 class="page-title mb-0">Payments</h4>
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
                                <h5 class="mb-3">Add payment</h5>
                                <form action="<?= site_url('portal/payments'); ?>" method="post">
                                    <div class="form-group">
                                        <label for="invoice_id">Invoice</label>
                                        <select id="invoice_id" name="invoice_id" class="form-control" required>
                                            <option value="">Select invoice</option>
                                            <?php foreach (($invoices ?? []) as $inv): ?>
                                                <option value="<?= (int)$inv->id; ?>">#<?= (int)$inv->id; ?> — <?= number_format((float)$inv->amount, 2); ?> (<?= htmlentities($inv->status); ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" step="0.01" id="amount" name="amount" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="method">Method</label>
                                        <input type="text" id="method" name="method" class="form-control" placeholder="Cash / Card / Insurance">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Payments</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Invoice</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($payments)): ?>
                                                <?php foreach ($payments as $row): ?>
                                                    <tr>
                                                        <td>#<?= (int)$row->invoice_id; ?> / <?= number_format((float)($row->invoice_amount ?? 0), 2); ?></td>
                                                        <td><?= number_format((float)($row->amount ?? 0), 2); ?></td>
                                                        <td><?= htmlentities($row->method ?? ''); ?></td>
                                                        <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-muted text-center">No payments yet.</td></tr>
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
