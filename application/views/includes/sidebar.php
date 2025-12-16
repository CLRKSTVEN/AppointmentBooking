<div class="left-side-menu">
    <div class="slimscroll-menu">
        <?php
        $currentUri = trim(uri_string(), '/');
        $role = strtolower((string) $this->session->userdata('role'));
        $isAdmin = $role === 'admin';

        $navItems = $isAdmin
            ? [
                ['route' => 'dashboard',      'label' => 'Dashboard overview', 'icon' => 'bi-speedometer2'],
                ['route' => 'dashboard/log',  'label' => 'Accomplishments',    'icon' => 'bi-journal-plus'],
                ['route' => 'register',       'label' => 'Register staff',     'icon' => 'bi-person-plus'],
            ]
            : [
                ['route' => 'dashboard',      'label' => 'My dashboard',       'icon' => 'bi-speedometer2'],
                ['route' => 'dashboard/log',  'label' => 'My accomplishments', 'icon' => 'bi-journal-plus'],
            ];
        ?>

        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Office Tracker</li>
                <?php foreach ($navItems as $meta): ?>
                    <?php
                    $routeKey = trim($meta['route'], '/');
                    $routePath = $routeKey;
                    $isExact = ($currentUri === $routePath);
                    $hasPrefix = (strpos($currentUri, $routePath . '/') === 0);
                    $isActive = $isExact || $hasPrefix;

                    // Prevent the base dashboard link from also lighting up on /dashboard/log
                    if ($routePath === 'dashboard' && strpos($currentUri, 'dashboard/log') === 0) {
                        $isActive = false;
                    }
                    $href = site_url($meta['route']);
                    if (!empty($meta['query'])) {
                        $href .= '?' . $meta['query'];
                    }
                    ?>
                    <li class="<?= $isActive ? 'active' : ''; ?>">
                        <a href="<?= $href; ?>" class="waves-effect">
                            <i class="bi <?= $meta['icon']; ?>"></i>
                            <span> <?= $meta['label']; ?> </span>
                        </a>
                    </li>
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
