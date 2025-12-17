<?php if ($this->session->userdata('level') !== 'Student'): ?>
  <li class="dropdown notification-list req-bell"
    data-count-url="<?= site_url('AppointmentNotify/ajax_pending_count'); ?>"
    data-list-url="<?= site_url('AppointmentNotify/ajax_pending_list'); ?>"
    data-markseen-url="<?= site_url('AppointmentNotify/ajax_mark_seen'); ?>"
    data-index-url="<?= site_url('dashboard_staff'); ?>">

    <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
      <i class="mdi mdi-bell-outline noti-icon"></i>
      <span class="badge badge-danger rounded-circle noti-icon-badge req-badge" style="display:none;">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-lg">
      <div class="dropdown-item noti-title d-flex justify-content-between align-items-center">
        <h5 class="font-16 m-0">New Appointments</h5>
        <a href="<?= site_url('dashboard_staff'); ?>" class="text-muted small">View all</a>
      </div>

      <div class="slimscroll noti-scroll" style="max-height:320px; overflow:auto;">
        <div class="text-center text-muted p-3 req-empty">No new appointments.</div>
        <div class="req-list"></div>
      </div>
    </div>
  </li>
<?php endif; ?>
