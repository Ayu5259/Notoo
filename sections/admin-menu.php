<nav class="menu admin-menu">
    <ul class="list-unstyled mb-0">
        <li>
            <a href="admin.php" class="menu-item<?php if (basename($_SERVER['PHP_SELF']) == 'admin.php') echo ' active'; ?>">
                <i class="fas fa-tachometer-alt text-primary"></i>
                <span>داشبورد</span>
            </a>
        </li>
        <li>
            <a href="admin-users.php" class="menu-item<?php if (basename($_SERVER['PHP_SELF']) == 'admin-users.php') echo ' active'; ?>">
                <i class="fas fa-users text-success"></i>
                <span>مدیریت کاربران</span>
            </a>
        </li>
        <li>
            <a href="admin-notes.php" class="menu-item<?php if (basename($_SERVER['PHP_SELF']) == 'admin-notes.php') echo ' active'; ?>">
                <i class="fas fa-sticky-note text-warning"></i>
                <span>مدیریت یادداشت‌ها</span>
            </a>
        </li>
        <li>
            <a href="admin-reports.php" class="menu-item<?php if (basename($_SERVER['PHP_SELF']) == 'admin-reports.php') echo ' active'; ?>">
                <i class="fas fa-chart-bar text-info"></i>
                <span>گزارش‌ها</span>
            </a>
        </li>
        <li>
            <a href="admin-settings.php" class="menu-item<?php if (basename($_SERVER['PHP_SELF']) == 'admin-settings.php') echo ' active'; ?>">
                <i class="fas fa-cog text-secondary"></i>
                <span>تنظیمات سیستم</span>
            </a>
        </li>
        <li class="menu-divider my-2"></li>
        <li>
            <a href="index.php" class="menu-item">
                <i class="fas fa-home text-dark"></i>
                <span>بازگشت به سایت</span>
            </a>
        </li>
        <li>
            <a href="?logout" class="menu-item">
                <i class="fas fa-sign-out-alt text-danger"></i>
                <span>خروج</span>
            </a>
        </li>
    </ul>
</nav>
<style>
    .admin-menu .menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 4px;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
    }

    .admin-menu .menu-item.active,
    .admin-menu .menu-item:hover {
        background: linear-gradient(90deg, #e0e7ff 0%, #f3e8ff 100%);
        color: #293462 !important;
    }

    .admin-menu .menu-item .badge {
        font-size: 0.8rem;
        margin-right: auto;
    }

    .menu-section-title {
        font-size: 0.95rem;
        color: #888;
        margin: 10px 0 4px 0;
        padding-right: 10px;
        letter-spacing: 1px;
    }

    .menu-divider {
        border-bottom: 1px solid #eee;
        margin: 10px 0;
    }
</style>