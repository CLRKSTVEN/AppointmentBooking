<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid appointments-wrap">

                    <style>
                        .appointments-wrap .row {
                            margin-bottom: 16px;
                        }

                        .appointments-titlebar {
                            align-items: flex-start;
                            gap: 12px;
                            flex-wrap: wrap;
                        }

                        .appointments-title-left {
                            display: flex;
                            flex-direction: column;
                            align-items: flex-start;
                            min-width: 260px;
                        }

                        .appointments-title-left .page-title {
                            line-height: 1.2;
                        }

                        .appointments-subline {
                            margin-top: 6px;
                        }

                        .appointments-meta {
                            margin-top: 10px;
                            display: flex;
                            gap: 10px;
                            flex-wrap: wrap;
                            align-items: center;
                        }

                        .appointments-time {
                            font-size: 1.1em;
                            line-height: 1.2;
                            margin-top: 2px;
                            white-space: nowrap;
                        }

                        .appointments-title-actions {
                            display: flex;
                            gap: 8px;
                            flex-wrap: wrap;
                            justify-content: flex-end;
                            align-items: flex-start;
                            margin-left: auto;
                        }

                        .card-box {
                            height: 100%;
                        }

                        .appointments-grid {
                            display: flex;
                            flex-wrap: wrap;
                        }

                        .appointments-grid>[class*="col-"] {
                            margin-bottom: 16px;
                        }

                        .form-row .form-group {
                            margin-bottom: 12px;
                        }

                        .table-responsive {
                            width: 100%;
                        }

                        /* Keep tables tidy */
                        .table td,
                        .table th {
                            vertical-align: middle;
                        }

                        .cell-ellipsis {
                            max-width: 180px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }

                        .cell-notes {
                            max-width: 260px;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }

                        .list-group-item .badge {
                            vertical-align: middle;
                        }

                        @media (max-width: 991.98px) {
                            .appointments-title-actions {
                                width: 100%;
                                justify-content: flex-start;
                            }
                        }
                    </style>

                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="page-title-box appointments-titlebar d-flex justify-content-between">
                                <div class="appointments-title-left">
                                    <h4 class="page-title mb-1">
                                        <?= ($is_admin || $is_staff) ? 'All consultations' : 'AppointFlow — Appointments'; ?>
                                    </h4>

                                    <p class="text-muted mb-0">
                                        <?= ($is_admin || $is_staff) ? 'View all consultations booked by clients.' : 'Log and manage your appointments.'; ?>
                                    </p>

                                    <div class="appointments-meta">
                                        <span class="badge badge-<?= $is_admin ? 'danger' : ($is_staff ? 'info' : 'primary'); ?>">
                                            <?= $is_admin ? 'Admin' : ($is_staff ? 'Staff' : 'Client'); ?> mode
                                        </span>
                                        <span id="ph-time-api" class="font-weight-bold text-primary appointments-time"></span>
                                    </div>
                                </div>

                                <?php if (!empty($overview_nav)): ?>
                                    <div class="appointments-title-actions">
                                        <?php foreach ($overview_nav as $nav): ?>
                                            <a class="btn btn-outline-primary btn-sm" href="<?= $nav['url']; ?>"><?= htmlentities($nav['label']); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex align-items-stretch">
                        <div class="col-lg-12">
                            <div class="card-box h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <div>
                                        <h5 class="mb-0"><?= ($is_admin || $is_staff) ? 'All appointments' : 'Your appointments'; ?></h5>
                                        <small class="text-muted"><?= ($is_admin || $is_staff) ? 'Manage and review all logged appointments.' : 'Log new appointments and track their status.'; ?></small>
                                    </div>
                                    <?php if ($is_client): ?>
                                        <button class="btn btn-primary" id="openAppointmentModal">
                                            <i class="mdi mdi-plus"></i> Log an appointment
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <?php if ($this->session->flashdata('error')): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?= $this->session->flashdata('error'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <?php if ($this->session->flashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?= $this->session->flashdata('success'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <?php $list = $accomplishments ?? []; ?>

                                <?php if ($is_admin || $is_staff): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered w-100" id="appointments-table">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1" class="cell-ellipsis">Title</th>
                                                    <th>Type</th>
                                                    <th class="cell-ellipsis">Client</th>
                                                    <th data-priority="4" class="cell-ellipsis">Contact</th>
                                                    <th data-priority="2" class="cell-ellipsis">Start</th>
                                                    <th class="cell-ellipsis">Location</th>
                                                    <th class="cell-ellipsis">Doctor</th>
                                                    <th class="cell-ellipsis">Insurance</th>
                                                    <th data-priority="5" class="cell-notes">Symptoms / Notes</th>
                                                    <th class="cell-ellipsis">Status</th>
                                                    <th data-priority="1">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($list as $row): ?>
                                                    <?php
                                                    $startRaw = (string)($row->start_date ?? '');
                                                    $startDatePart = $startRaw;
                                                    if ($startRaw && strtotime($startRaw)) {
                                                        $startDatePart = date('Y-m-d H:i', strtotime($startRaw));
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="cell-ellipsis" title="<?= htmlentities($row->title ?? 'Untitled'); ?>"><?= htmlentities($row->title ?? 'Untitled'); ?></td>
                                                        <td><?= htmlentities($row->category ?? ''); ?></td>
                                                        <td class="cell-ellipsis" title="<?= htmlentities(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')); ?>"><?= htmlentities(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')); ?></td>
                                                        <td class="cell-ellipsis" title="<?= htmlentities($row->client_email ?? ''); ?>"><?= htmlentities($row->client_email ?? ''); ?></td>
                                                        <td class="cell-ellipsis" title="<?= htmlentities($startDatePart); ?>"><?= htmlentities($startDatePart); ?></td>
                                                        <td class="cell-ellipsis" title="<?= htmlentities($row->location ?? ''); ?>"><?= htmlentities($row->location ?? ''); ?></td>
                                                        <td class="cell-ellipsis" title="<?= htmlentities($row->doctor ?? ''); ?>"><?= htmlentities($row->doctor ?? ''); ?></td>
                                                        <td class="cell-ellipsis" title="<?= htmlentities($row->insurance_provider ?? ''); ?>"><?= htmlentities($row->insurance_provider ?? ''); ?></td>
                                                        <td class="text-muted small cell-notes" title="<?= htmlentities(trim(($row->symptoms ?? '') . ' ' . ($row->description ?? ''))); ?>">
                                                            <?php if (!empty($row->symptoms)): ?>
                                                                <div><strong>Symptoms:</strong> <?= htmlentities($row->symptoms); ?></div>
                                                            <?php endif; ?>
                                                            <?php if (!empty($row->description)): ?>
                                                                <div><strong>Notes:</strong> <?= htmlentities($row->description); ?></div>
                                                            <?php endif; ?>
                                                        </td>
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
                                                                <small>Processed<?= !empty($row->processor_first) ? ' by ' . htmlentities(($row->processor_first ?? '') . ' ' . ($row->processor_last ?? '')) : ''; ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered w-100" id="client-appointments-table">
                                            <thead>
                                                <tr>
                                                    <th class="cell-ellipsis" data-priority="1">Title</th>
                                                    <th class="cell-ellipsis">Type</th>
                                                    <th class="cell-ellipsis" data-priority="2">Start</th>
                                                    <th class="cell-ellipsis">Location</th>
                                                    <th class="cell-ellipsis">Doctor</th>
                                                    <th class="cell-ellipsis">Status</th>
                                                    <th data-priority="1">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($list)): ?>
                                                    <?php foreach ($list as $row): ?>
                                                        <?php
                                                        $startRaw = (string)($row->start_date ?? '');
                                                        $startDateVal = $startRaw;
                                                        $startDateDisplay = $startRaw;
                                                        $startTimeVal = '';
                                                        if ($startRaw && strtotime($startRaw)) {
                                                            $startDateVal = date('Y-m-d', strtotime($startRaw));
                                                            $startTimeVal = date('H:i', strtotime($startRaw));
                                                            $startDateDisplay = trim($startDateVal . ($startTimeVal ? ' ' . $startTimeVal : ''));
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="cell-ellipsis" title="<?= htmlentities($row->title ?? 'Untitled'); ?>"><?= htmlentities($row->title ?? 'Untitled'); ?></td>
                                                            <td class="cell-ellipsis" title="<?= htmlentities($row->category ?? ''); ?>"><?= htmlentities($row->category ?? ''); ?></td>
                                                            <td class="cell-ellipsis" title="<?= htmlentities($startDateDisplay); ?>"><?= htmlentities($startDateDisplay); ?></td>
                                                            <td class="cell-ellipsis" title="<?= htmlentities($row->location ?? ''); ?>"><?= htmlentities($row->location ?? ''); ?></td>
                                                            <td class="cell-ellipsis" title="<?= htmlentities($row->doctor ?? ''); ?>"><?= htmlentities($row->doctor ?? ''); ?></td>
                                                            <td class="cell-ellipsis" title="<?= htmlentities($row->status ?? 'pending'); ?>">
                                                                <span class="badge <?= ($row->status ?? 'pending') === 'accepted' ? 'badge-success' : (($row->status ?? 'pending') === 'declined' ? 'badge-danger' : 'badge-secondary'); ?>">
                                                                    <?= htmlentities($row->status ?? 'pending'); ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm" role="group">
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-outline-primary edit-appointment"
                                                                        data-id="<?= (int)($row->id ?? 0); ?>"
                                                                        data-title="<?= htmlentities($row->title ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-category="<?= htmlentities($row->category ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-location="<?= htmlentities($row->location ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-doctor="<?= htmlentities($row->doctor ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-insurance="<?= htmlentities($row->insurance_provider ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-date="<?= htmlentities($startDateVal ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-time="<?= htmlentities($startTimeVal ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-symptoms="<?= htmlentities($row->symptoms ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-payment="<?= htmlentities($row->payment_reference ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-description="<?= htmlentities($row->description ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-public="<?= (int)($row->is_public ?? 0); ?>"
                                                                    >
                                                                        <i class="mdi mdi-pencil"></i>
                                                                    </button>
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-outline-danger delete-appointment"
                                                                        data-url="<?= site_url('dashboard/log/delete/' . ($row->id ?? 0)); ?>"
                                                                    >
                                                                        <i class="mdi mdi-delete"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        <?php if (empty($list)): ?>
                                            <div class="text-muted small p-2">No appointments yet.</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($is_client): ?>
                        <!-- Appointment Modal -->
                        <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form id="appointmentForm" method="post" action="<?= site_url('dashboard/log/save'); ?>" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="appointmentModalLabel">Log an appointment</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" id="appointmentId">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="modalTitle">Title</label>
                                                    <input id="modalTitle" name="title" class="form-control" required placeholder="Add a short title for this appointment">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="modalCategory">Appointment type</label>
                                                    <select id="modalCategory" name="category" class="form-control" required>
                                                        <option value="">Select appointment type</option>
                                                        <?php if (!empty($appointment_types)): ?>
                                                            <?php foreach ($appointment_types as $type): ?>
                                                                <option value="<?= htmlentities($type->name); ?>"><?= htmlentities($type->name); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="modalLocation">Location</label>
                                                    <select id="modalLocation" name="location" class="form-control" required>
                                                        <option value="">Select room/location</option>
                                                        <?php if (!empty($appointment_rooms)): ?>
                                                            <?php foreach ($appointment_rooms as $room): ?>
                                                                <option value="<?= htmlentities($room->name); ?>"><?= htmlentities($room->name); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="modalDoctor">Preferred doctor</label>
                                                    <select id="modalDoctor" name="doctor" class="form-control">
                                                        <option value="">Any available</option>
                                                        <?php if (!empty($doctors)): ?>
                                                            <?php foreach ($doctors as $doc): ?>
                                                                <option value="<?= htmlentities($doc->name); ?>"><?= htmlentities($doc->name); ?><?= $doc->specialty ? ' — ' . htmlentities($doc->specialty) : ''; ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="modalStart">Start date</label>
                                                    <input type="date" id="modalStart" name="start_date" class="form-control" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="modalTime">Time</label>
                                                    <input type="time" id="modalTime" name="start_time" class="form-control" required>
                                                </div>
                                            </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="modalInsurance">Insurance provider</label>
                                                <input type="text" id="modalInsurance" name="insurance_provider" class="form-control" placeholder="Insurance provider name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="modalPayment">Receipt / reference (optional)</label>
                                                <input type="text" id="modalPayment" name="payment_reference" class="form-control" placeholder="Receipt or insurance reference">
                                            </div>
                                        </div>

                                            <div class="form-group">
                                                <label for="modalSymptoms">Symptoms / reason for visit</label>
                                                <textarea id="modalSymptoms" name="symptoms" class="form-control" rows="2" required placeholder="Briefly describe symptoms or reason"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="modalDescription">Notes</label>
                                                <textarea id="modalDescription" name="description" class="form-control" rows="3" placeholder="What is this appointment about?"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="modalAttachment">Upload referral/receipt (PDF/JPG/PNG)</label>
                                                <input type="file" id="modalAttachment" name="attachment" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                                            </div>

                                            <div class="form-group">
                                                <label for="modalVisibility">Visibility</label>
                                                <select id="modalVisibility" name="is_public" class="form-control">
                                                    <option value="1" selected>Public</option>
                                                    <option value="0">Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary" id="appointmentSubmitBtn">Save appointment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <?php include('includes/themecustomizer.php'); ?>
    <?php include('includes/footer_plugins.php'); ?>
    <script>
        (function() {
            if (window.jQuery && $.fn.DataTable) {
                if ($('#appointments-table').length) {
                    $('#appointments-table').DataTable({
                        pageLength: 10,
                        responsive: true,
                        autoWidth: false,
                        order: [
                            [4, 'desc']
                        ],
                        columnDefs: [
                            { targets: [3, 7, 8], render: $.fn.dataTable.render.ellipsis(30, true) },
                            { targets: -1, orderable: false, searchable: false }
                        ]
                    });
                }
                if ($('#client-appointments-table').length) {
                    $('#client-appointments-table').DataTable({
                        pageLength: 10,
                        responsive: true,
                        autoWidth: false,
                        order: [
                            [2, 'desc']
                        ]
                    });
                }
            }

            // Client modal handlers
            $('#openAppointmentModal').on('click', function() {
                resetAppointmentForm();
                $('#appointmentModalLabel').text('Log an appointment');
                $('#appointmentSubmitBtn').text('Save appointment');
                $('#appointmentForm').attr('action', '<?= site_url('dashboard/log/save'); ?>');
                $('#appointmentModal').modal('show');
            });

            $(document).on('click', '.edit-appointment', function() {
                var btn = $(this);
                resetAppointmentForm();
                $('#appointmentId').val(btn.data('id'));
                $('#modalTitle').val(btn.data('title'));
                $('#modalCategory').val(btn.data('category'));
                $('#modalLocation').val(btn.data('location'));
                $('#modalDoctor').val(btn.data('doctor'));
                $('#modalInsurance').val(btn.data('insurance'));
                $('#modalStart').val(btn.data('date'));
                $('#modalTime').val(btn.data('time'));
                $('#modalSymptoms').val(btn.data('symptoms'));
                $('#modalPayment').val(btn.data('payment'));
                $('#modalDescription').val(btn.data('description'));
                $('#modalVisibility').val(btn.data('public'));

                $('#appointmentModalLabel').text('Edit appointment');
                $('#appointmentSubmitBtn').text('Update appointment');
                $('#appointmentForm').attr('action', '<?= site_url('dashboard/log/update'); ?>');
                $('#appointmentModal').modal('show');
            });

            $(document).on('click', '.delete-appointment', function() {
                var url = $(this).data('url');
                if (!url) return;
                if (confirm('Are you sure you want to delete this appointment?')) {
                    window.location = url;
                }
            });

            function resetAppointmentForm() {
                $('#appointmentForm')[0].reset();
                $('#appointmentId').val('');
                $('#modalAttachment').val('');
                $('#modalVisibility').val('1');
            }
        })();
    </script>
</body>

</html>
