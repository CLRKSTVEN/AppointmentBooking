<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

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
                                <h4 class="page-title mb-0">Change profile photo</h4>
                                <a href="<?= site_url('Page/staffprofile'); ?>" class="btn btn-outline-primary btn-sm">
                                    &larr; Back to profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($upload_success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlentities($upload_success); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($upload_error) || $this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= !empty($upload_error) ? htmlentities($upload_error) : htmlentities($this->session->flashdata('error')); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-box">
                                <?php
                                $fullName = trim(
                                    ($profile->first_name ?? '') . ' ' .
                                    (!empty($profile->middle_name) ? substr($profile->middle_name, 0, 1) . '. ' : '') .
                                    ($profile->last_name ?? '') . ' ' .
                                    ($profile->suffix ?? '')
                                );
                                $fullName = $fullName !== '' ? $fullName : ($profile->username ?? 'My profile');
                                $photoSrc = base_url('upload/profile/' . ($profile->photo ?? 'avatar.png'));
                                ?>
                                <div class="media align-items-center mb-3">
                                    <img src="<?= $photoSrc; ?>" alt="Profile photo" class="rounded-circle mr-3" width="96" height="96">
                                    <div class="media-body">
                                        <h5 class="mt-0 mb-1"><?= htmlentities($fullName); ?></h5>
                                        <div class="text-muted small"><?= htmlentities($profile->username ?? ''); ?></div>
                                        <div class="text-muted small"><?= htmlentities(ucfirst($profile->role ?? '')); ?></div>
                                    </div>
                                </div>

                                <form action="<?= site_url('Page/changeDP'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="photo">Upload a new photo</label>
                                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*" required>
                                        <small class="form-text text-muted">PNG, JPG, or GIF up to 2 MB.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save new photo</button>
                                    <a href="<?= site_url('Page/staffprofile'); ?>" class="btn btn-link">Cancel</a>
                                </form>
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
