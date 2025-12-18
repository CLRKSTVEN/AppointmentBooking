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
                                <div>
                                    <h4 class="page-title mb-0">My profile</h4>
                                    <small class="text-muted">View your account details and photo.</small>
                                </div>
                                <a href="<?= site_url('Page/changeDP'); ?>" class="btn btn-primary btn-sm">Change photo</a>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlentities($success); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlentities($error); ?>
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
                                $roleLabel = ucfirst((string) ($profile->role ?? ''));
                                $photoSrc = base_url('upload/profile/' . ($profile->photo ?? 'avatar.png'));
                                ?>
                                <div class="media mb-3 align-items-center">
                                    <img src="<?= $photoSrc; ?>" alt="Profile photo" class="rounded-circle mr-3" width="96" height="96">
                                    <div class="media-body">
                                        <h4 class="mt-0 mb-1"><?= htmlentities($fullName); ?></h4>
                                        <?php if ($roleLabel !== ''): ?>
                                            <span class="badge badge-info"><?= htmlentities($roleLabel); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="text-muted small">Email / username</div>
                                            <div><?= htmlentities($profile->username ?? ''); ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="text-muted small">Position</div>
                                            <div><?= htmlentities($profile->position_title ?? 'Not set'); ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="text-muted small">Office</div>
                                            <div><?= htmlentities($profile->office_name ?? ($profile->office_id ? 'Office #' . $profile->office_id : 'Not set')); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="text-muted small">Account created</div>
                                            <div><?= !empty($profile->created_at) ? htmlentities($profile->created_at) : '—'; ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="text-muted small">Short bio / notes</div>
                                            <div><?= !empty($profile->short_bio) ? nl2br(htmlentities($profile->short_bio)) : '<span class="text-muted">Add a short bio from registration.</span>'; ?></div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($is_own_profile)): ?>
                                    <hr>
                                    <h5 class="mb-3">Edit profile details</h5>
                                    <form action="<?= site_url('Page/staffprofile'); ?>" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="first_name">First name</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" required value="<?= htmlentities($profile->first_name ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="last_name">Last name</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" required value="<?= htmlentities($profile->last_name ?? ''); ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="middle_name">Middle name</label>
                                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= htmlentities($profile->middle_name ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="suffix">Suffix</label>
                                                <input type="text" class="form-control" id="suffix" name="suffix" placeholder="Jr / Sr / III" value="<?= htmlentities($profile->suffix ?? ''); ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="position_title">Position</label>
                                                <select id="position_title" name="position_title" class="form-control">
                                                    <option value="">Select position</option>
                                                    <?php if (!empty($positions)): ?>
                                                        <?php foreach ($positions as $pos): ?>
                                                            <option value="<?= htmlentities($pos); ?>" <?= (!empty($profile->position_title) && $profile->position_title === $pos) ? 'selected' : ''; ?>>
                                                                <?= htmlentities($pos); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="office_id">Office</label>
                                                <select id="office_id" name="office_id" class="form-control">
                                                    <option value="">Select office</option>
                                                    <?php if (!empty($offices)): ?>
                                                        <?php foreach ($offices as $office): ?>
                                                            <option value="<?= (int)$office->id; ?>" <?= (!empty($profile->office_id) && (int)$profile->office_id === (int)$office->id) ? 'selected' : ''; ?>>
                                                                <?= htmlentities($office->name ?? ('Office #' . $office->id)); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="short_bio">Short bio / notes</label>
                                            <textarea class="form-control" id="short_bio" name="short_bio" rows="3" placeholder="Area of expertise, services provided"><?= htmlentities($profile->short_bio ?? ''); ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                <?php endif; ?>

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
