           <!-- <script type="text/javascript"> 
        window.history.forward(); 
        function noBack() { 
            window.history.forward(); 
        } 
    </script> -->

<?php
$role = strtolower((string) $this->session->userdata('role'));
$displayName = trim((string) $this->session->userdata('full_name'));
if ($displayName === '') {
    $displayName = (string) $this->session->userdata('username');
}
$avatarFile = $this->session->userdata('photo') ?? $this->session->userdata('avatar') ?? 'avatar.png';
$avatarUrl = base_url('upload/profile/' . $avatarFile);
?>

           <div class="navbar-custom">
               <ul class="list-unstyled topnav-menu float-right mb-0">
                   <?php include(APPPATH . 'views/includes/appointment_bell.php'); ?>
                   <li class="dropdown notification-list">
                       <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                           <i class="mdi mdi-message-text-outline noti-icon"></i>
                           <span id="msg-badge" class="badge badge-danger rounded-circle noti-icon-badge" style="display:none;">0</span>
                       </a>
                       <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                           <div class="dropdown-item noti-title d-flex justify-content-between align-items-center">
                               <h5 class="font-16 m-0">Messages</h5>
                               <a href="<?= site_url('messages'); ?>" class="text-muted small">Open</a>
                           </div>
                           <div class="p-2">
                               <a href="<?= site_url('messages'); ?>" class="btn btn-primary btn-block btn-sm">Go to inbox</a>
                           </div>
                       </div>
                   </li>
                   <li class="dropdown notification-list">
                       <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                           <img src="<?= $avatarUrl; ?>" alt="user-image" class="rounded-circle">
                           <span class="pro-user-name ml-1">
                               <?= htmlentities($displayName); ?> <i class="mdi mdi-chevron-down"></i>
                           </span>
                       </a>
                       <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                           <a href="<?= site_url('Page/changeDP'); ?>" class="dropdown-item notify-item">
                               <i class="mdi mdi-settings-outline"></i>
                               <span>Change Profile Pic</span>
                           </a>

                           <a href="<?= site_url('Page/staffprofile'); ?>" class="dropdown-item notify-item">
                               <i class="mdi mdi-account-outline"></i>
                               <span>My Profile</span>
                           </a>

                           <a href="<?= site_url('Page/lockScreen'); ?>" class="dropdown-item notify-item">
                               <i class="mdi mdi-lock-outline"></i>
                               <span>Lock Screen</span>
                           </a>

                           <div class="dropdown-divider"></div>

                           <a href="<?= site_url('login/logout'); ?>" class="dropdown-item notify-item">
                               <i class="mdi mdi-logout-variant"></i>
                               <span>Logout</span>
                           </a>

                       </div>
                   </li>

                   <li class="dropdown notification-list">
                       <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect">
                           <i class="mdi mdi-settings-outline noti-icon"></i>
                       </a>
                   </li>


               </ul>

               <!-- LOGO -->
               <div class="logo-box">
                   <a href="#" class="logo text-center logo-dark">
                       <span class="logo-lg">
                           <img src="<?= base_url(); ?>assets/images/srms-logo-1.png" alt="" height="18">
                           <!-- <span class="logo-lg-text-dark">Velonic</span> -->
                       </span>
                       <span class="logo-sm">
                           <!-- <span class="logo-lg-text-dark">V</span> -->
                           <img src="<?= base_url(); ?>assets/images/Attendance.png" alt="" height="30">
                       </span>
                   </a>

                   <a href="#" class="logo text-center logo-light">
                       <span class="logo-lg">
                           <img src="<?= base_url(); ?>assets/images/srms-logo-1.png" alt="" height="45">
                           <!-- <span class="logo-lg-text-dark">Velonic</span> -->
                       </span>
                       <span class="logo-sm">
                           <!-- <span class="logo-lg-text-dark">V</span> -->
                           <img src="<?= base_url(); ?>assets/images/Attendance.png" alt="" height="30">
                       </span>
                   </a>
               </div>

               <!-- LOGO -->


               <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                   <li>
                       <button class="button-menu-mobile waves-effect">
                           <i class="mdi mdi-menu"></i>
                       </button>
                   </li>

                   <li class="d-none d-lg-block">
                       <form class="app-search">
                           <div class="app-search-box">
                               <div class="input-group">
                                   <input type="text" class="form-control" placeholder="Search...">
                                   <div class="input-group-append">
                                       <button class="btn" type="submit">
                                           <i class="fas fa-search"></i>
                                       </button>
                                   </div>
                               </div>
                           </div>
                       </form>
                   </li>
               </ul>
           </div>

<script>
(function() {
    // Lightweight polling for unread message count
    function updateMsgBadge() {
        if (!window.jQuery) return;
        $.getJSON('<?= site_url('messages/unread_count'); ?>')
            .done(function(res) {
                var badge = $('#msg-badge');
                var count = (res && typeof res.count !== 'undefined') ? parseInt(res.count, 10) : 0;
                if (count > 0) {
                    badge.text(count > 99 ? '99+' : count);
                    badge.show();
                } else {
                    badge.hide();
                }
            });
    }
    if (window.jQuery) {
        $(document).ready(function() {
            updateMsgBadge();
            setInterval(updateMsgBadge, 8000);
        });
    } else {
        // Retry once jQuery loads
        var checkInterval = setInterval(function() {
            if (window.jQuery) {
                clearInterval(checkInterval);
                updateMsgBadge();
                setInterval(updateMsgBadge, 8000);
            }
        }, 500);
    }
})();
</script>
