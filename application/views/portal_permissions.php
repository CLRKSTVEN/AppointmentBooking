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
                                    <h4 class="page-title mb-0">Permissions</h4>
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
                                <h5 class="mb-3">Add permission</h5>
                                <form action="<?= site_url('portal/permissions'); ?>" method="post">
                                    <input type="hidden" name="action" value="add-permission">
                                    <div class="form-group">
                                        <label for="perm_name">Permission</label>
                                        <input type="text" id="perm_name" name="name" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Assign to role</h5>
                                <form action="<?= site_url('portal/permissions'); ?>" method="post" class="mb-3">
                                    <input type="hidden" name="action" value="assign">
                                    <div class="form-row">
                                        <div class="form-group col-md-5">
                                            <label for="role_id">Role</label>
                                            <select id="role_id" name="role_id" class="form-control" required>
                                                <option value="">Select</option>
                                                <?php foreach (($roles ?? []) as $role): ?>
                                                    <option value="<?= (int)$role->id; ?>"><?= htmlentities($role->name ?? ''); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="permission_id">Permission</label>
                                            <select id="permission_id" name="permission_id" class="form-control" required>
                                                <option value="">Select</option>
                                                <?php foreach (($permissions ?? []) as $perm): ?>
                                                    <option value="<?= (int)$perm->id; ?>"><?= htmlentities($perm->name ?? ''); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary btn-block">Assign</button>
                                        </div>
                                    </div>
                                </form>

                                <h5 class="mb-2">Role permissions</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th>Permission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($role_permissions)): ?>
                                                <?php foreach ($role_permissions as $rp): ?>
                                                    <?php
                                                    $roleName = '';
                                                    foreach ($roles as $r) { if ((int)$r->id === (int)$rp->role_id) { $roleName = $r->name; break; } }
                                                    $permName = '';
                                                    foreach ($permissions as $p) { if ((int)$p->id === (int)$rp->permission_id) { $permName = $p->name; break; } }
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlentities($roleName); ?></td>
                                                        <td><?= htmlentities($permName); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="2" class="text-muted text-center">No assignments yet.</td></tr>
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
