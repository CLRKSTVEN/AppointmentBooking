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
                                    <h4 class="page-title mb-0">Doctor Schedules</h4>
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
                                <h5 class="mb-3">Add schedule</h5>
                                <form action="<?= site_url('portal/schedules'); ?>" method="post">
                                    <div class="form-group">
                                        <label for="doctor_id">Doctor</label>
                                        <select id="doctor_id" name="doctor_id" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php foreach (($doctors ?? []) as $doc): ?>
                                                <option value="<?= (int)$doc->id; ?>"><?= htmlentities($doc->name ?? ''); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="day_of_week">Day</label>
                                        <select id="day_of_week" name="day_of_week" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day): ?>
                                                <option value="<?= $day; ?>"><?= $day; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="start_time">Start</label>
                                            <input type="time" id="start_time" name="start_time" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="end_time">End</label>
                                            <input type="time" id="end_time" name="end_time" class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-box">
                                <h5 class="mb-3">Schedules</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Day</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($schedules)): ?>
                                                <?php foreach ($schedules as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities($row->doctor_name ?? ''); ?></td>
                                                        <td><?= htmlentities($row->day_of_week ?? ''); ?></td>
                                                        <td><?= htmlentities(($row->start_time ?? '') . ' - ' . ($row->end_time ?? '')); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="3" class="text-muted text-center">No schedules yet.</td></tr>
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
