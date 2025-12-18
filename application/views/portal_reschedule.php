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
                                    <h4 class="page-title mb-0">Reschedule appointment</h4>
                                </div>
                                <a href="<?= site_url('portal/appointments'); ?>" class="btn btn-outline-primary btn-sm">&larr; Back</a>
                            </div>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlentities($this->session->flashdata('error')); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="card-box">
                        <?php
                        $startRaw = $appointment->start_date ?? '';
                        $startDate = $startRaw && strtotime($startRaw) ? date('Y-m-d', strtotime($startRaw)) : '';
                        $startTime = $startRaw && strtotime($startRaw) ? date('H:i', strtotime($startRaw)) : '';
                        ?>
                        <form action="<?= site_url('portal/reschedule/' . (int)$appointment->id); ?>" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Date</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required value="<?= htmlentities($startDate); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="start_time">Time</label>
                                    <input type="time" id="start_time" name="start_time" class="form-control" required value="<?= htmlentities($startTime); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" id="location" name="location" class="form-control" value="<?= htmlentities($appointment->location ?? ''); ?>" placeholder="Room/clinic">
                            </div>
                            <div class="form-group">
                                <label for="doctor">Preferred doctor</label>
                                <input type="text" id="doctor" name="doctor" class="form-control" value="<?= htmlentities($appointment->doctor ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <a href="<?= site_url('portal/appointments'); ?>" class="btn btn-link">Cancel</a>
                        </form>
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
