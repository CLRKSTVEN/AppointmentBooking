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
                                    <h4 class="page-title mb-0">Online Appointment Booking — Appointments</h4>
                                    <p class="text-muted mb-0">Log and manage your appointments.</p>
                                </div>
                                <?php if (!empty($overview_nav)): ?>
                                    <div>
                                        <?php foreach ($overview_nav as $nav): ?>
                                            <a class="btn btn-outline-primary btn-sm" href="<?= $nav['url']; ?>"><?= htmlentities($nav['label']); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-box">
                                <h5 class="mb-3">Log an appointment</h5>
                                <form method="post" action="<?= site_url('dashboard/log/save'); ?>">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input id="title" name="title" class="form-control" required placeholder="Consultation with client">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="category">Category</label>
                                            <input id="category" name="category" class="form-control" list="category-options" placeholder="Dental / Advisory / Follow-up">
                                            <?php if (!empty($accomplishment_categories)): ?>
                                                <datalist id="category-options">
                                                    <?php foreach ($accomplishment_categories as $cat): ?>
                                                        <option value="<?= htmlentities($cat); ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="location">Location</label>
                                            <input id="location" name="location" class="form-control" placeholder="Room 201 or Zoom" list="address-options">
                                            <?php if (!empty($addresses)): ?>
                                                <datalist id="address-options">
                                                    <?php foreach ($addresses as $addr): ?>
                                                        <option value="<?= htmlentities($addr->label ?? $addr->address_line ?? ''); ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="start_date">Start date</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="end_date">End date</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Notes</label>
                                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="What is this appointment about?"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_public">Visibility</label>
                                        <select id="is_public" name="is_public" class="form-control">
                                            <option value="1" selected>Public</option>
                                            <option value="0">Private</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save appointment</button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Your appointments</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($accomplishments)): ?>
                                        <?php foreach ($accomplishments as $row): ?>
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong><?= htmlentities($row->title ?? 'Untitled'); ?></strong><br>
                                                        <span class="text-muted small"><?= htmlentities($row->category ?? 'General'); ?><?php if (!empty($row->start_date)): ?> · <?= htmlentities($row->start_date); ?><?php endif; ?></span><br>
                                                        <span class="text-muted small"><?= htmlentities($row->description ?? ''); ?></span>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="badge <?= ($row->is_public ?? 0) ? 'badge-primary' : 'badge-secondary'; ?>">
                                                            <?= ($row->is_public ?? 0) ? 'Public' : 'Private'; ?>
                                                        </span>
                                                        <div class="mt-2">
                                                            <a href="<?= site_url('dashboard/log/delete/' . ($row->id ?? 0)); ?>" class="text-danger small">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-muted">No appointments yet.</li>
                                    <?php endif; ?>
                                </ul>
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
