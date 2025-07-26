<style>
    .sidebar-container {
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        transition: width 0.3s ease;
        background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
        border-right: 1px solid #dee2e6;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }

    .sidebar-expanded {
        width: 280px;
    }

    .sidebar-collapsed {
        width: 60px;
    }

    .sidebar-header {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 70px;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        color: #495057;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .sidebar-brand img {
        width: 32px;
        height: 32px;
        margin-right: 10px;
    }

    .sidebar-toggle {
        background: none;
        border: none;
        color: #6c757d;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .sidebar-nav {
        padding: 1rem 0;
        height: calc(100vh - 70px);
        overflow-y: auto;
    }

    .nav-item {
        margin-bottom: 0.25rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #6c757d;
        text-decoration: none;
        border-radius: 0;
        transition: all 0.3s ease;
        position: relative;
        margin: 0 0.5rem;
        border-radius: 0.5rem;
    }

    .nav-link:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .nav-link.active {
        background-color: #fd7e14;
        color: white;
    }

    .nav-link.active:hover {
        background-color: #e8590c;
        color: white;
    }

    .nav-icon {
        width: 20px;
        height: 20px;
        margin-right: 0.75rem;
        text-align: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .nav-text {
        flex: 1;
        transition: opacity 0.3s ease;
    }

    .nav-arrow {
        font-size: 0.8rem;
        transition: transform 0.3s ease;
        margin-left: auto;
    }

    .nav-arrow.expanded {
        transform: rotate(90deg);
    }

    /* Show down arrow when expanded, right arrow when collapsed */
    .nav-link[aria-expanded="true"] .nav-arrow {
        transform: rotate(270deg);
    }

    .nav-link[aria-expanded="false"] .nav-arrow {
        transform: rotate(90deg);
    }

    .nav-submenu {
        padding-left: 0;
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .nav-submenu .nav-link {
        padding: 0.5rem 1rem 0.5rem 3rem;
        font-size: 0.9rem;
        color: #6c757d;
        margin: 0 0.5rem;
    }

    .nav-submenu .nav-link:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .nav-submenu .nav-link.active {
        background-color: #fd7e14;
        color: white;
    }

    .sidebar-collapsed .nav-text,
    .sidebar-collapsed .nav-arrow,
    .sidebar-collapsed .sidebar-brand span {
        opacity: 0;
        width: 0;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .sidebar-collapsed .nav-submenu {
        display: none;
    }

    .sidebar-collapsed .nav-link {
        justify-content: center;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .sidebar-collapsed .nav-icon {
        margin-right: 0;
    }

    .sidebar-collapsed .sidebar-brand {
        justify-content: center;
    }

    .sidebar-collapsed .sidebar-brand img {
        margin-right: 0;
    }

    .sidebar-collapsed .sidebar-toggle {
        position: absolute;
        right: -15px;
        top: 50%;
        transform: translateY(-50%);
        background: #fd7e14;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .sidebar-collapsed .sidebar-toggle:hover {
        background: #e8590c;
    }

    .content-wrapper {
        margin-left: 280px;
        transition: margin-left 0.3s ease;
        min-height: 100vh;
    }

    .content-wrapper.sidebar-collapsed {
        margin-left: 60px;
    }

    .nav-divider {
        height: 1px;
        background-color: #dee2e6;
        margin: 0.5rem 1rem;
    }

    /* Scrollbar styling */
    .sidebar-nav::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 2px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: #adb5bd;
    }

    /* Tooltip for collapsed sidebar */
    .sidebar-collapsed .nav-link[data-bs-toggle="tooltip"] {
        position: relative;
    }

    /* Flyover menu for collapsed sidebar */
    .nav-flyover {
        position: absolute;
        left: 100%;
        top: 0;
        min-width: 200px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px);
        transition: all 0.2s ease;
        margin-left: 10px;
    }

    .nav-flyover.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .nav-flyover::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 15px;
        width: 0;
        height: 0;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        border-right: 6px solid white;
    }

    .nav-flyover::after {
        content: '';
        position: absolute;
        left: -7px;
        top: 15px;
        width: 0;
        height: 0;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        border-right: 6px solid #dee2e6;
    }

    .flyover-header {
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #495057;
        border-bottom: 1px solid #dee2e6;
        background: #f8f9fa;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .flyover-item {
        display: block;
        padding: 0.5rem 1rem;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f8f9fa;
    }

    .flyover-item:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    .flyover-item.active {
        background-color: #fd7e14;
        color: white;
    }

    .flyover-item:last-child {
        border-bottom: none;
        border-radius: 0 0 0.5rem 0.5rem;
    }

    /* Only show flyover when sidebar is collapsed */
    .sidebar-expanded .nav-flyover {
        display: none;
    }

    .sidebar-collapsed .nav-item {
        position: relative;
    }

    @media (max-width: 768px) {
        .sidebar-container {
            transform: translateX(-100%);
        }
        
        .sidebar-container.mobile-open {
            transform: translateX(0);
        }
        
        .content-wrapper {
            margin-left: 0;
        }

        .nav-flyover {
            display: none !important;
        }
    }
</style>

<div class="sidebar-container sidebar-expanded" id="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-brand">
            <img src="/img/badgerstaffpanel-logo.png" alt="Logo">
            <span>{{ \Illuminate\Support\Facades\Session::get("server_name") }}</span>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">


            <li class="nav-item">
                <a href="/web/verified/dashboard" class="nav-link {{ request()->is('web/verified/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#playersSubmenu" aria-expanded="false">
                    <i class="fas fa-users nav-icon"></i>
                    <span class="nav-text">Players</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-flyover" id="playersFlyover">
                    <div class="flyover-header">Players</div>
                    <a href="/web/verified/players/today" class="flyover-item {{ request()->is('web/verified/players/today') ? 'active' : '' }}">Today</a>
                    <a href="/web/verified/players/week" class="flyover-item {{ request()->is('web/verified/players/week') ? 'active' : '' }}">Weekly</a>
                    <a href="/web/verified/players/month" class="flyover-item {{ request()->is('web/verified/players/month') ? 'active' : '' }}">Monthly</a>
                    <a href="/web/verified/players" class="flyover-item {{ request()->is('web/verified/players') ? 'active' : '' }}">All Players</a>
                </div>
                <div class="collapse nav-submenu" id="playersSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="/web/verified/players/today" class="nav-link {{ request()->is('web/verified/players/today') ? 'active' : '' }}">
                                Today
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/players/week" class="nav-link {{ request()->is('web/verified/players/week') ? 'active' : '' }}">
                                Weekly
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/players/month" class="nav-link {{ request()->is('web/verified/players/month') ? 'active' : '' }}">
                                Monthly
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/players" class="nav-link {{ request()->is('web/verified/players') ? 'active' : '' }}">
                                All Players
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#recordsSubmenu" aria-expanded="false">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <span class="nav-text">Records</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-flyover" id="recordsFlyover">
                    <div class="flyover-header">Records</div>
                    <a href="/web/verified/records/bans" class="flyover-item {{ request()->is('web/verified/records/bans') ? 'active' : '' }}">Bans</a>
                    <a href="/web/verified/records/warns" class="flyover-item {{ request()->is('web/verified/records/warns') ? 'active' : '' }}">Warns</a>
                    <a href="/web/verified/records/kicks" class="flyover-item {{ request()->is('web/verified/records/kicks') ? 'active' : '' }}">Kicks</a>
                    <a href="/web/verified/records/commends" class="flyover-item {{ request()->is('web/verified/records/commends') ? 'active' : '' }}">Commends</a>
                    <a href="/web/verified/records/trustscores" class="flyover-item {{ request()->is('web/verified/records/trustscores') ? 'active' : '' }}">Trust Scores</a>
                    <a href="/web/verified/records" class="flyover-item {{ request()->is('web/verified/records') ? 'active' : '' }}">All Records</a>
                </div>
                <div class="collapse nav-submenu" id="recordsSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="/web/verified/records/bans" class="nav-link {{ request()->is('web/verified/records/bans') ? 'active' : '' }}">
                                Bans
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/records/warns" class="nav-link {{ request()->is('web/verified/records/warns') ? 'active' : '' }}">
                                Warns
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/records/kicks" class="nav-link {{ request()->is('web/verified/records/kicks') ? 'active' : '' }}">
                                Kicks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/records/commends" class="nav-link {{ request()->is('web/verified/records/commends') ? 'active' : '' }}">
                                Commends
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/records/trustscores" class="nav-link {{ request()->is('web/verified/records/trustscores') ? 'active' : '' }}">
                                Trust Scores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/records" class="nav-link {{ request()->is('web/verified/records') ? 'active' : '' }}">
                                All Records
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <div class="nav-divider"></div>

            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#managementSubmenu" aria-expanded="false">
                    <i class="fas fa-cogs nav-icon"></i>
                    <span class="nav-text">Management</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-flyover" id="managementFlyover">
                    <div class="flyover-header">Management</div>
                    <a href="/web/verified/management/manage_staff" class="flyover-item {{ request()->is('web/verified/management/manage_staff') ? 'active' : '' }}">Manage Staff</a>
                    <a href="/web/verified/management/manage_roles" class="flyover-item {{ request()->is('web/verified/management/manage_roles') ? 'active' : '' }}">Manage Roles</a>
                    <a href="/web/verified/management/manage_tokens" class="flyover-item {{ request()->is('web/verified/management/manage_tokens') ? 'active' : '' }}">Manage Tokens</a>
                    <a href="/web/verified/management/settings" class="flyover-item {{ request()->is('web/verified/management/settings') ? 'active' : '' }}">Settings</a>
                </div>
                <div class="collapse nav-submenu" id="managementSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="/web/verified/management/manage_staff" class="nav-link {{ request()->is('web/verified/management/manage_staff') ? 'active' : '' }}">
                                Manage Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/management/manage_roles" class="nav-link {{ request()->is('web/verified/management/manage_roles') ? 'active' : '' }}">
                                Manage Roles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/management/manage_tokens" class="nav-link {{ request()->is('web/verified/management/manage_tokens') ? 'active' : '' }}">
                                Manage Tokens
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/management/settings" class="nav-link {{ request()->is('web/verified/management/settings') ? 'active' : '' }}">
                                Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <div class="nav-divider"></div>

            <li class="nav-item">
                <a href="/web/verified/signout" class="nav-link">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <span class="nav-text">Sign out</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const contentWrapper = document.querySelector('.content-wrapper');

    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('sidebar-collapsed');
        sidebar.classList.toggle('sidebar-expanded');
        
        if (contentWrapper) {
            contentWrapper.classList.toggle('sidebar-collapsed');
        }

        // Hide all flyovers when expanding sidebar
        if (sidebar.classList.contains('sidebar-expanded')) {
            document.querySelectorAll('.nav-flyover').forEach(flyover => {
                flyover.classList.remove('show');
            });
        }

        // Update toggle icon
        const icon = sidebarToggle.querySelector('i');
        if (sidebar.classList.contains('sidebar-collapsed')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-chevron-right');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-bars');
        }
    });

    // Handle submenu arrows with Bootstrap collapse events
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(element) {
        const targetId = element.getAttribute('data-bs-target');
        const collapseElement = document.querySelector(targetId);
        
        if (collapseElement) {
            collapseElement.addEventListener('show.bs.collapse', function() {
                element.setAttribute('aria-expanded', 'true');
            });
            
            collapseElement.addEventListener('hide.bs.collapse', function() {
                element.setAttribute('aria-expanded', 'false');
            });
        }
    });

    // Initialize tooltips for collapsed sidebar
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle flyover menus for collapsed sidebar
    function setupFlyovers() {
        const flyoverItems = [
            { trigger: '[data-bs-target="#playersSubmenu"]', flyover: '#playersFlyover' },
            { trigger: '[data-bs-target="#recordsSubmenu"]', flyover: '#recordsFlyover' },
            { trigger: '[data-bs-target="#managementSubmenu"]', flyover: '#managementFlyover' }
        ];

        flyoverItems.forEach(item => {
            const triggerElement = document.querySelector(item.trigger);
            const flyoverElement = document.querySelector(item.flyover);
            
            if (triggerElement && flyoverElement) {
                let hoverTimeout;
                
                // Show flyover on mouse enter
                triggerElement.parentElement.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                    
                    // Only show flyover if sidebar is collapsed
                    if (sidebar.classList.contains('sidebar-collapsed')) {
                        hoverTimeout = setTimeout(() => {
                            flyoverElement.classList.add('show');
                        }, 100);
                    }
                });
                
                // Hide flyover on mouse leave
                triggerElement.parentElement.addEventListener('mouseleave', function() {
                    clearTimeout(hoverTimeout);
                    hoverTimeout = setTimeout(() => {
                        flyoverElement.classList.remove('show');
                    }, 100);
                });
                
                // Keep flyover open when hovering over it
                flyoverElement.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                });
                
                // Hide flyover when leaving it
                flyoverElement.addEventListener('mouseleave', function() {
                    clearTimeout(hoverTimeout);
                    hoverTimeout = setTimeout(() => {
                        flyoverElement.classList.remove('show');
                    }, 100);
                });
            }
        });
    }

    // Initialize flyovers
    setupFlyovers();

    // Handle mobile responsive
    function handleResize() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('sidebar-collapsed');
            sidebar.classList.remove('sidebar-expanded');
            if (contentWrapper) {
                contentWrapper.classList.add('sidebar-collapsed');
            }
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize(); // Initial check
});
</script>
