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
                                    <h4 class="page-title mb-0">
                                        <?= ($is_admin || $is_staff) ? 'All consultations' : 'Online Appointment Booking — Appointments'; ?>
                                    </h4>
                                    <p class="text-muted mb-0">
                                        <?= ($is_admin || $is_staff) ? 'View all consultations booked by clients.' : 'Log and manage your appointments.'; ?>
                                    </p>
                                    <span class="badge badge-<?= $is_admin ? 'danger' : ($is_staff ? 'info' : 'primary'); ?> mt-2">
                                        <?= $is_admin ? 'Admin' : ($is_staff ? 'Staff' : 'Client'); ?> mode
                                    </span>
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
                        <?php if ($is_client): ?>
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <h5 class="mb-3">Log an appointment</h5>
                                    <form method="post" action="<?= site_url('dashboard/log/save'); ?>">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input id="title" name="title" class="form-control" required placeholder="Describe your appointment (e.g., Consultation)">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="category">Appointment type</label>
                                                <select id="category" name="category" class="form-control" required>
                                                    <option value="">Select appointment type</option>
                                                    <?php if (!empty($appointment_types)): ?>
                                                        <?php foreach ($appointment_types as $type): ?>
                                                            <option value="<?= htmlentities($type->name); ?>"><?= htmlentities($type->name); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                            <label for="location">Location</label>
                                            <select id="location" name="location" class="form-control" required>
                                                <option value="">Select room/location</option>
                                                <?php if (!empty($appointment_rooms)): ?>
                                                    <?php foreach ($appointment_rooms as $room): ?>
                                                        <option value="<?= htmlentities($room->name); ?>"><?= htmlentities($room->name); ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="doctor">Preferred doctor</label>
                                            <select id="doctor" name="doctor" class="form-control">
                                                <option value="">Any available</option>
                                                <?php if (!empty($doctors)): ?>
                                                    <?php foreach ($doctors as $doc): ?>
                                                        <option value="<?= htmlentities($doc->name); ?>"><?= htmlentities($doc->name); ?><?= $doc->specialty ? ' — ' . htmlentities($doc->specialty) : ''; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="insurance_provider">Insurance provider</label>
                                            <input type="text" id="insurance_provider" name="insurance_provider" class="form-control" placeholder="e.g., PhilHealth / private">
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
                                            <label for="symptoms">Symptoms / reason for visit</label>
                                            <textarea id="symptoms" name="symptoms" class="form-control" rows="2" required placeholder="Briefly describe symptoms or reason"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="payment_reference">Receipt / reference (optional)</label>
                                            <input type="text" id="payment_reference" name="payment_reference" class="form-control" placeholder="Receipt or insurance reference">
                                        </div>
                                        <div class="form-group">
                                            <label for="attachment">Upload referral/receipt (PDF/JPG/PNG)</label>
                                            <input type="file" id="attachment" name="attachment" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
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
                        <?php endif; ?>

                        <div class="<?= ($is_admin || $is_staff) ? 'col-lg-12' : 'col-lg-6'; ?>">
                            <div class="card-box">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0"><?= ($is_admin || $is_staff) ? 'All appointments' : 'Your appointments'; ?></h5>
                                </div>

                                <?php $list = $accomplishments ?? []; ?>

                                <?php if ($is_admin || $is_staff): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="appointments-table">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Type</th>
                                                    <th>Client</th>
                                                    <th>Start</th>
                                                    <th>Location</th>
                                                    <th>Doctor</th>
                                                    <th>Insurance</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($list as $row): ?>
                                                    <tr>
                                                        <td><?= htmlentities($row->title ?? 'Untitled'); ?></td>
                                                    <td><?= htmlentities($row->category ?? ''); ?></td>
                                                    <td><?= htmlentities(trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''))); ?></td>
                                                    <td><?= htmlentities($row->start_date ?? ''); ?></td>
                                                    <td><?= htmlentities($row->location ?? ''); ?></td>
                                                    <td><?= htmlentities($row->doctor ?? ''); ?></td>
                                                    <td><?= htmlentities($row->insurance_provider ?? ''); ?></td>
                                                    <td>
                                                        <span class="badge badge-<?= ($row->status ?? 'pending') === 'accepted' ? 'success' : (($row->status ?? 'pending') === 'declined' ? 'danger' : 'secondary'); ?>">
                                                            <?= htmlentities($row->status ?? 'pending'); ?>
                                                        </span>
                                                    </td>
                                                        <td>
                                                            <?php if (($row->status ?? '') === 'pending'): ?>
                                                                <form method="post" action="<?= site_url('dashboard/log/status'); ?>" style="display:inline;">
                                                                    <input type="hidden" name="id" value="<?= (int)($row->id ?? 0); ?>">
                                                                    <input type="hidden" name="status" value="accepted">
                                                                    <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                                                </form>
                                                                <form method="post" action="<?= site_url('dashboard/log/status'); ?>" style="display:inline;">
                                                                    <input type="hidden" name="id" value="<?= (int)($row->id ?? 0); ?>">
                                                                    <input type="hidden" name="status" value="declined">
                                                                    <button type="submit" class="btn btn-sm btn-danger">Decline</button>
                                                                </form>
                                                            <?php else: ?>
                                                                <small>Processed<?= !empty($row->processor_first) ? ' by ' . htmlentities(trim($row->processor_first . ' ' . $row->processor_last)) : ''; ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <?php
                                    // Find the earliest upcoming appointment for the reminder
                                    $nextAppt = null;
                                    $today = date('Y-m-d');
                                    foreach ($list as $row) {
                                        $sd = $row->start_date ?? null;
                                        if ($sd && $sd >= $today) {
                                            if ($nextAppt === null || $sd < $nextAppt->start_date) {
                                                $nextAppt = $row;
                                            }
                                        }
                                    }
                                    if ($nextAppt === null && !empty($list)) {
                                        $nextAppt = $list[0];
                                    }
                                    ?>
                                    <?php if ($nextAppt): ?>
                                        <div class="alert alert-info">
                                            <strong>Reminder:</strong> Your next appointment is on <?= htmlentities($nextAppt->start_date ?? ''); ?>
                                            <?php if (!empty($nextAppt->location)): ?>
                                                at <?= htmlentities($nextAppt->location); ?>
                                            <?php endif; ?>
                                            (<?= htmlentities($nextAppt->title ?? ''); ?>)
                                        </div>
                                    <?php endif; ?>
                                    <ul class="list-group list-group-flush">
                                        <?php if (!empty($list)): ?>
                                            <?php foreach ($list as $row): ?>
                                                <li class="list-group-item">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <strong><?= htmlentities($row->title ?? 'Untitled'); ?></strong><br>
                                                            <span class="text-muted small">
                                                                <?= htmlentities($row->category ?? 'General'); ?>
                                                                <?php if (!empty($row->start_date)): ?> · <?= htmlentities($row->start_date); ?><?php endif; ?>
                                                            </span><br>
                                                            <span class="text-muted small">
                                                                Status:
                                                                <span class="badge <?= ($row->status ?? 'pending') === 'accepted' ? 'badge-success' : (($row->status ?? 'pending') === 'declined' ? 'badge-danger' : 'badge-secondary'); ?>">
                                                                    <?= htmlentities($row->status ?? 'pending'); ?>
                                                                </span>
                                                            </span><br>
                                                            <?php if (!empty($row->symptoms)): ?>
                                                                <span class="text-muted small">Symptoms: <?= htmlentities($row->symptoms); ?></span><br>
                                                            <?php endif; ?>
                                                            <?php if (!empty($row->doctor)): ?>
                                                                <span class="text-muted small">Doctor: <?= htmlentities($row->doctor); ?></span><br>
                                                            <?php endif; ?>
                                                            <?php if (!empty($row->insurance_provider)): ?>
                                                                <span class="text-muted small">Insurance: <?= htmlentities($row->insurance_provider); ?></span><br>
                                                            <?php endif; ?>
                                                            <?php if (!empty($row->payment_reference)): ?>
                                                                <span class="text-muted small">Receipt: <?= htmlentities($row->payment_reference); ?></span><br>
                                                            <?php endif; ?>
                                                            <?php if (!empty($row->attachment)): ?>
                                                                <span class="text-muted small"><a href="<?= base_url('upload/appointments/' . $row->attachment); ?>" target="_blank">View attachment</a></span><br>
                                                            <?php endif; ?>
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
    <?php if ($is_admin || $is_staff): ?>
    <script>
        (function() {
            if (window.jQuery && $.fn.DataTable) {
                $('#appointments-table').DataTable({
                    pageLength: 10,
                    order: [[3, 'desc']]
                });
            }
        })();
    </script>
    <?php endif; ?>
</body>

</html>
