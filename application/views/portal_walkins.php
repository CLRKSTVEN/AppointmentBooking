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
                                    <h4 class="page-title mb-0">Walk-in Patients</h4>
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
                                <h5 class="mb-3">Add walk-in</h5>
                                <form action="<?= site_url('portal/walkins'); ?>" method="post">
                                    <input type="hidden" name="action" value="add">
                                    <div class="form-group">
                                        <label for="name">Patient name</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="reason">Reason / notes</label>
                                        <textarea id="reason" name="reason" class="form-control" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Log walk-in</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Queue</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($walkins)): ?>
                                                <?php foreach ($walkins as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities($row->name ?? ''); ?></td>
                                                        <td><?= htmlentities($row->reason ?? ''); ?></td>
                                                        <td><span class="badge badge-<?= ($row->status ?? 'queued') === 'attended' ? 'success' : 'secondary'; ?>">
                                                            <?= htmlentities($row->status ?? 'queued'); ?>
                                                        </span></td>
                                                        <td><?= htmlentities($row->created_at ?? ''); ?></td>
                                                        <td>
                                                            <?php if (($row->status ?? 'queued') !== 'attended'): ?>
                                                                <form action="<?= site_url('portal/walkins'); ?>" method="post" class="d-inline">
                                                                    <input type="hidden" name="action" value="update_status">
                                                                    <input type="hidden" name="id" value="<?= (int)$row->id; ?>">
                                                                    <input type="hidden" name="status" value="attended">
                                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark attended</button>
                                                                </form>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-muted text-center">No walk-ins yet.</td></tr>
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
