<div class="left-side-menu">
    <div class="slimscroll-menu">
        <?php
        $currentUri = trim(uri_string(), '/');
        $role = strtolower((string) $this->session->userdata('role'));
        $isAdmin = $role === 'admin';
        $isStaff = $role === 'staff';

        if ($isAdmin) {
            $navItems = [
                ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'dashboard'],
                ['label' => 'User Management', 'icon' => 'bi-people', 'children' => [
                    ['route' => 'portal/manage-staff', 'label' => 'Manage Staff'],
                    ['route' => 'portal/manage-patients', 'label' => 'Manage Patients'],
                    ['route' => 'portal/page/roles-permissions', 'label' => 'Roles & Permissions'],
                ]],
                ['label' => 'Appointments', 'icon' => 'bi-calendar-event', 'children' => [
                    ['route' => 'dashboard/log', 'label' => 'All Appointments'],
                    ['route' => 'portal/page/booking-rules', 'label' => 'Booking Rules & Limits'],
                ]],
                ['label' => 'Departments & Services', 'icon' => 'bi-building', 'children' => [
                    ['route' => 'portal/page/departments', 'label' => 'Departments'],
                    ['route' => 'portal/doctors', 'label' => 'Doctors & Schedules'],
                    ['route' => 'portal/services', 'label' => 'Medical Services'],
                ]],
                ['label' => 'Billing & Finance', 'icon' => 'bi-credit-card', 'children' => [
                    ['route' => 'portal/billing', 'label' => 'Payments'],
                    ['route' => 'portal/page/invoices', 'label' => 'Invoices'],
                    ['route' => 'portal/page/revenue-reports', 'label' => 'Revenue Reports'],
                ]],
                ['label' => 'Reports & Analytics', 'icon' => 'bi-graph-up', 'children' => [
                    ['route' => 'portal/reports/appointments', 'label' => 'Appointment Reports'],
                    ['route' => 'portal/reports/patients', 'label' => 'Patient Statistics'],
                    ['route' => 'portal/page/audit-logs', 'label' => 'Audit Logs'],
                ]],
                ['label' => 'System Settings', 'icon' => 'bi-gear', 'children' => [
                    ['route' => 'portal/hospital-info', 'label' => 'Hospital Information'],
                    ['route' => 'portal/page/clinic-hours', 'label' => 'Clinic Hours'],
                    ['route' => 'portal/page/notifications', 'label' => 'Notifications'],
                    ['route' => 'portal/page/backup-restore', 'label' => 'Backup & Restore'],
                ]],
                ['label' => 'Security', 'icon' => 'bi-shield-lock', 'children' => [
                    ['route' => 'portal/page/access-logs', 'label' => 'Access Logs'],
                    ['route' => 'portal/page/account-status', 'label' => 'Account Status'],
                ]],
                ['route' => 'messages', 'label' => 'Messages', 'icon' => 'bi-chat-dots'],
            ];
        } elseif ($isStaff) {
            $navItems = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2'],
                ['label' => 'Appointments', 'icon' => 'bi-calendar-check', 'children' => [
                    ['route' => 'dashboard/log', 'label' => 'Appointment Schedule'],
                    ['route' => 'portal/manage-appointments', 'label' => 'Approve / Reject Appointments'],
                    ['route' => 'portal/walkins', 'label' => 'Walk-in Patients'],
                ]],
                ['label' => 'Patients', 'icon' => 'bi-people', 'children' => [
                    ['route' => 'portal/patient-list', 'label' => 'Patient List'],
                    ['route' => 'portal/page/patient-medical-records', 'label' => 'Patient Medical Records'],
                    ['route' => 'portal/page/consultation-notes', 'label' => 'Consultation Notes'],
                ]],
                ['label' => 'Medical Services', 'icon' => 'bi-capsule', 'children' => [
                    ['route' => 'portal/prescriptions', 'label' => 'Prescriptions'],
                    ['route' => 'portal/lab-requests', 'label' => 'Lab Requests / Results'],
                ]],
                ['label' => 'Billing', 'icon' => 'bi-cash-coin', 'children' => [
                    ['route' => 'portal/billing', 'label' => 'Billing Overview'],
                ]],
                ['label' => 'Reports', 'icon' => 'bi-bar-chart', 'children' => [
                    ['route' => 'portal/page/daily-monthly-reports', 'label' => 'Daily / Monthly Reports'],
                ]],
                ['route' => 'messages', 'label' => 'Messages', 'icon' => 'bi-chat-dots'],
            ];
        } else {
            // Client nav
            $navItems = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2'],
                ['label' => 'Appointments', 'icon' => 'bi-calendar', 'children' => [
                    ['route' => 'dashboard/log', 'label' => 'Book Appointment'],
                    ['route' => 'portal/appointments', 'label' => 'My Appointments'],
                    ['route' => 'portal/page/cancel-reschedule', 'label' => 'Cancel / Reschedule'],
                ]],
                ['route' => 'messages', 'label' => 'Messages', 'icon' => 'bi-chat-dots'],
                ['label' => 'Medical Records', 'icon' => 'bi-folder2', 'children' => [
                    ['route' => 'portal/my-medical-records', 'label' => 'My Medical Records'],
                    ['route' => 'portal/page/lab-results', 'label' => 'Lab Results'],
                    ['route' => 'portal/my-prescriptions', 'label' => 'Prescriptions'],
                ]],
                ['label' => 'Billing', 'icon' => 'bi-credit-card', 'children' => [
                    ['route' => 'portal/billing', 'label' => 'Billing & Payments'],
                    ['route' => 'portal/page/payment-history', 'label' => 'Payment History'],
                ]],
                ['label' => 'Profile', 'icon' => 'bi-person', 'children' => [
                    ['route' => 'Page/staffprofile', 'label' => 'My Profile'],
                    ['route' => 'portal/page/change-password', 'label' => 'Change Password'],
                ]],
                ['label' => 'Support', 'icon' => 'bi-life-preserver', 'children' => [
                    ['route' => 'portal/contact', 'label' => 'Contact Hospital'],
                    ['route' => 'portal/page/help-faq', 'label' => 'Help / FAQs'],
                ]],
            ];
        }
        ?>

        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">AppointFlow</li>
                <?php foreach ($navItems as $meta): ?>
                    <?php if (empty($meta['children'])): ?>
                        <?php
                        $routeKey = trim($meta['route'], '/');
                        $routePath = $routeKey;
                        $isExact = ($currentUri === $routePath);
                        $hasPrefix = (strpos($currentUri, $routePath . '/') === 0);
                        $isActive = $isExact || $hasPrefix;
                        if ($routePath === 'dashboard' && strpos($currentUri, 'dashboard/log') === 0) {
                            $isActive = false;
                        }
                        $href = $meta['route'] === '#' ? 'javascript:void(0);' : site_url($meta['route']);
                        ?>
                        <li class="<?= $isActive ? 'active' : ''; ?>">
                            <a href="<?= $href; ?>" class="waves-effect">
                                <i class="bi <?= $meta['icon']; ?>"></i>
                                <span> <?= $meta['label']; ?> </span>
                            </a>
                        </li>
                    <?php else: ?>
                        <?php
                        $sectionActive = false;
                        foreach ($meta['children'] as $child) {
                            $childRoute = trim($child['route'], '/');
                            if ($childRoute !== '' && ($currentUri === $childRoute || strpos($currentUri, $childRoute . '/') === 0)) {
                                $sectionActive = true;
                            }
                        }
                        ?>
                        <li class="<?= $sectionActive ? 'active' : ''; ?>">
                            <a href="javascript:void(0);" class="waves-effect" aria-expanded="<?= $sectionActive ? 'true' : 'false'; ?>">
                                <i class="bi <?= $meta['icon']; ?>"></i>
                                <span> <?= $meta['label']; ?> </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="<?= $sectionActive ? 'true' : 'false'; ?>">
                                <?php foreach ($meta['children'] as $child): ?>
                                    <?php
                                    $childRoute = trim($child['route'], '/');
                                    $childHref = $child['route'] === '#' ? 'javascript:void(0);' : site_url($child['route']);
                                    $childActive = $childRoute !== '' && ($currentUri === $childRoute || strpos($currentUri, $childRoute . '/') === 0);
                                    ?>
                                    <li class="<?= $childActive ? 'active' : ''; ?>">
                                        <a href="<?= $childHref; ?>"><?= htmlentities($child['label']); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li>
                    <a href="<?= site_url('login/logout'); ?>" class="waves-effect">
                        <i class="bi bi-box-arrow-right"></i>
                        <span> Logout </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
