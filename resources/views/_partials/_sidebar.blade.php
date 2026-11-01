<style>
    .sidebar-container {
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        transition: width 0.3s ease;
        background: var(--bsp-sidebar, #12151c);
        border-right: 1px solid var(--bsp-sidebar-border, rgba(255, 255, 255, 0.06));
        box-shadow: none;
    }

    .nav-section-label {
        padding: 0.85rem 1.25rem 0.3rem;
        font-size: 0.68rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--bsp-sidebar-muted, #6b7280);
        font-weight: 700;
    }

    .sidebar-collapsed .nav-section-label {
        display: none;
    }

    .sidebar-expanded {
        width: 280px;
    }

    .sidebar-collapsed {
        width: 60px;
    }

    .sidebar-header {
        padding: 1rem;
        border-bottom: 1px solid var(--bsp-sidebar-border, rgba(255, 255, 255, 0.06));
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 70px;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        color: var(--bsp-sidebar-text-strong, #f3f4f6);
        font-weight: 600;
        font-size: 1.05rem;
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
        color: var(--bsp-sidebar-text, #9ca3af);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
        background-color: rgba(255, 255, 255, 0.06);
        color: #f3f4f6;
    }

    .sidebar-nav {
        padding: 0.75rem 0 1.25rem;
        height: calc(100vh - 132px);
        overflow-y: auto;
    }

    .nav-item {
        margin-bottom: 0.25rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.55rem 0.85rem;
        color: var(--bsp-sidebar-text, #9ca3af);
        text-decoration: none;
        border-radius: 0;
        transition: all 0.2s ease;
        position: relative;
        margin: 0 0.5rem;
        border-radius: 0.6rem;
        font-weight: 500;
    }

    .nav-link:hover {
        background-color: var(--bsp-sidebar-hover, rgba(255, 255, 255, 0.05));
        color: var(--bsp-sidebar-text-strong, #f3f4f6);
    }

    .nav-link.active,
    .nav-submenu .nav-link.active {
        background: var(--bsp-sidebar-active-bg, rgba(249, 115, 22, 0.14));
        color: var(--bsp-sidebar-active, #fdba74);
        box-shadow: none;
    }

    .nav-link.active:hover,
    .nav-submenu .nav-link.active:hover {
        background: var(--bsp-sidebar-active-bg, rgba(249, 115, 22, 0.14));
        color: var(--bsp-sidebar-active, #fdba74);
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

    /* Tailwind's .collapse utility sets visibility:collapse and hides these links. */
    .nav-submenu.collapse,
    .nav-submenu.collapsing {
        visibility: visible;
    }

    .nav-submenu .nav-link {
        padding: 0.45rem 1rem 0.45rem 2.75rem;
        font-size: 0.88rem;
        color: var(--bsp-sidebar-text, #9ca3af);
        margin: 0 0.5rem;
    }

    .nav-submenu .nav-link:hover {
        background-color: var(--bsp-sidebar-hover, rgba(255, 255, 255, 0.05));
        color: var(--bsp-sidebar-text-strong, #f3f4f6);
    }

    .nav-submenu .nav-link.active {
        background: var(--bsp-sidebar-active-bg, rgba(249, 115, 22, 0.14));
        color: var(--bsp-sidebar-active, #fdba74);
        box-shadow: none;
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

    .sidebar-collapsed .nav-submenu,
    .sidebar-collapsed .sidebar-search {
        display: none;
    }

    .sidebar-collapsed .sidebar-nav {
        height: calc(100vh - 70px);
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
        width: calc(100% - 280px);
        max-width: calc(100% - 280px);
        transition: margin-left 0.3s ease, width 0.3s ease;
        min-height: 100vh;
        box-sizing: border-box;
    }

    .content-wrapper.sidebar-collapsed {
        margin-left: 60px;
        width: calc(100% - 60px);
        max-width: calc(100% - 60px);
    }

    .nav-divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.06);
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
        background: rgba(255, 255, 255, 0.12);
        border-radius: 2px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.22);
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
            width: 100%;
            max-width: 100%;
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

    <div class="sidebar-search">
        <i class="fas fa-search"></i>
        <input type="search" id="panelPlayerSearch" placeholder="Lookup player..." autocomplete="off">
        <div class="sidebar-search-results" id="panelPlayerResults"></div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-section-label">Home</li>

            <li class="nav-item">
                <a href="/web/verified/dashboard" class="nav-link {{ request()->is('web/verified/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('OVERVIEW') }}" class="nav-link {{ request()->is('web/verified/overview') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon"></i>
                    <span class="nav-text">Overview</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('LOOKUP') }}" class="nav-link {{ request()->is('web/verified/lookup') ? 'active' : '' }}">
                    <i class="fas fa-search nav-icon"></i>
                    <span class="nav-text">Lookup</span>
                </a>
            </li>

            <li class="nav-section-label">Players</li>
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
                    <a href="{{ route('RECENT_JOINS') }}" class="flyover-item {{ request()->is('web/verified/players/joins') ? 'active' : '' }}">Recent Joins</a>
                    <a href="{{ route('PLAYERS_ONLINE') }}" class="flyover-item {{ request()->is('web/verified/players/online') ? 'active' : '' }}">Online</a>
                    <a href="{{ route('LOW_TRUST') }}" class="flyover-item {{ request()->is('web/verified/players/low-trust') ? 'active' : '' }}">Low Trust</a>
                    <a href="{{ route('WATCHLIST') }}" class="flyover-item {{ request()->is('web/verified/watchlist') ? 'active' : '' }}">Watchlist</a>
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
                        <li class="nav-item">
                            <a href="{{ route('RECENT_JOINS') }}" class="nav-link {{ request()->is('web/verified/players/joins') ? 'active' : '' }}">
                                Recent Joins
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('PLAYERS_ONLINE') }}" class="nav-link {{ request()->is('web/verified/players/online') ? 'active' : '' }}">
                                Online
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('LOW_TRUST') }}" class="nav-link {{ request()->is('web/verified/players/low-trust') ? 'active' : '' }}">
                                Low Trust
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('WATCHLIST') }}" class="nav-link {{ request()->is('web/verified/watchlist') ? 'active' : '' }}">
                                Watchlist
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-section-label">Records</li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#recordsSubmenu" aria-expanded="false">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <span class="nav-text">Records</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-flyover" id="recordsFlyover">
                    <div class="flyover-header">Records</div>
                    <a href="{{ route('ACTIVE_BANS') }}" class="flyover-item {{ request()->is('web/verified/records/active-bans') ? 'active' : '' }}">Active Bans</a>
                    <a href="{{ route('EXPIRING_BANS') }}" class="flyover-item {{ request()->is('web/verified/records/expiring-bans') ? 'active' : '' }}">Ending Soon</a>
                    <a href="/web/verified/records/bans" class="flyover-item {{ request()->is('web/verified/records/bans') ? 'active' : '' }}">Bans</a>
                    <a href="/web/verified/records/warns" class="flyover-item {{ request()->is('web/verified/records/warns') ? 'active' : '' }}">Warns</a>
                    <a href="/web/verified/records/kicks" class="flyover-item {{ request()->is('web/verified/records/kicks') ? 'active' : '' }}">Kicks</a>
                    <a href="/web/verified/records/commends" class="flyover-item {{ request()->is('web/verified/records/commends') ? 'active' : '' }}">Commends</a>
                    <a href="/web/verified/records/trustscores" class="flyover-item {{ request()->is('web/verified/records/trustscores') ? 'active' : '' }}">Trust Scores</a>
                    <a href="/web/verified/records/notes" class="flyover-item {{ request()->is('web/verified/records/notes') ? 'active' : '' }}">Notes</a>
                    <a href="/web/verified/records/activity" class="flyover-item {{ request()->is('web/verified/records/activity') ? 'active' : '' }}">Activity</a>
                    <a href="/web/verified/records" class="flyover-item {{ request()->is('web/verified/records') ? 'active' : '' }}">All Records</a>
                    <a href="{{ route('SHOW_BANS') }}" class="flyover-item {{ request()->is('web/bans') ? 'active' : '' }}">Public Bans</a>
                </div>
                <div class="collapse nav-submenu" id="recordsSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('ACTIVE_BANS') }}" class="nav-link {{ request()->is('web/verified/records/active-bans') ? 'active' : '' }}">
                                Active Bans
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('EXPIRING_BANS') }}" class="nav-link {{ request()->is('web/verified/records/expiring-bans') ? 'active' : '' }}">
                                Ending Soon
                            </a>
                        </li>
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
                            <a href="/web/verified/records/notes" class="nav-link {{ request()->is('web/verified/records/notes') ? 'active' : '' }}">
                                Notes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/records/activity" class="nav-link {{ request()->is('web/verified/records/activity') ? 'active' : '' }}">
                                Activity
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/web/verified/records" class="nav-link {{ request()->is('web/verified/records') ? 'active' : '' }}">
                                All Records
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('SHOW_BANS') }}" class="nav-link {{ request()->is('web/bans') ? 'active' : '' }}">
                                Public Bans
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <div class="nav-divider"></div>
            <li class="nav-section-label">Staff</li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#managementSubmenu" aria-expanded="false">
                    <i class="fas fa-cogs nav-icon"></i>
                    <span class="nav-text">Management</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-flyover" id="managementFlyover">
                    <div class="flyover-header">Management</div>
                    <a href="{{ route('SERVER_INFO') }}" class="flyover-item {{ request()->is('web/verified/server') ? 'active' : '' }}">Server</a>
                    <a href="{{ route('STAFF_DIRECTORY') }}" class="flyover-item {{ request()->is('web/verified/management/staff') ? 'active' : '' }}">Staff Directory</a>
                    <a href="{{ route('SHOW_PROFILE') }}" class="flyover-item {{ request()->is('web/profile') ? 'active' : '' }}">My Profile</a>
                    <a href="{{ route('STAFF_PERFORMANCE') }}" class="flyover-item {{ request()->is('web/verified/management/performance') ? 'active' : '' }}">Staff Performance</a>
                    <a href="/web/verified/management/manage_staff" class="flyover-item {{ request()->is('web/verified/management/manage_staff') ? 'active' : '' }}">Manage Staff</a>
                    <a href="/web/verified/management/manage_roles" class="flyover-item {{ request()->is('web/verified/management/manage_roles') ? 'active' : '' }}">Manage Roles</a>
                    <a href="/web/verified/management/manage_tokens" class="flyover-item {{ request()->is('web/verified/management/manage_tokens') ? 'active' : '' }}">Manage Tokens</a>
                    <a href="/web/verified/management/settings" class="flyover-item {{ request()->is('web/verified/management/settings') ? 'active' : '' }}">Settings</a>
                    <a href="{{ route('install.show', ['step' => 'welcome']) }}" class="flyover-item {{ request()->is('web/install*') ? 'active' : '' }}">Installer</a>
                </div>
                <div class="collapse nav-submenu" id="managementSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('SERVER_INFO') }}" class="nav-link {{ request()->is('web/verified/server') ? 'active' : '' }}">
                                Server
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('STAFF_DIRECTORY') }}" class="nav-link {{ request()->is('web/verified/management/staff') ? 'active' : '' }}">
                                Staff Directory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('SHOW_PROFILE') }}" class="nav-link {{ request()->is('web/profile') ? 'active' : '' }}">
                                My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('STAFF_PERFORMANCE') }}" class="nav-link {{ request()->is('web/verified/management/performance') ? 'active' : '' }}">
                                Staff Performance
                            </a>
                        </li>
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
                        <li class="nav-item">
                            <a href="{{ route('install.show', ['step' => 'welcome']) }}" class="nav-link {{ request()->is('web/install*') ? 'active' : '' }}">
                                Installer
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <div class="nav-divider"></div>

            <li class="nav-item">
                <button type="button" class="nav-link theme-toggle" id="themeToggle">
                    <i class="fas fa-moon nav-icon" id="themeIcon"></i>
                    <span class="nav-text" id="themeLabel">Dark mode</span>
                </button>
            </li>
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

    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeLabel = document.getElementById('themeLabel');

    function syncThemeButton(theme) {
        const dark = theme === 'dark';
        if (themeIcon) {
            themeIcon.className = 'fas nav-icon ' + (dark ? 'fa-sun' : 'fa-moon');
        }
        if (themeLabel) {
            themeLabel.textContent = dark ? 'Light mode' : 'Dark mode';
        }
    }

    syncThemeButton(document.documentElement.getAttribute('data-theme') || 'light');

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('bsp-theme', next);
            syncThemeButton(next);
        });
    }

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

    const lookupInput = document.getElementById('panelPlayerSearch');
    const lookupResults = document.getElementById('panelPlayerResults');
    let lookupTimer = null;
    if (lookupInput && lookupResults) {
        lookupInput.addEventListener('input', function () {
            clearTimeout(lookupTimer);
            const q = this.value.trim();
            if (q.length < 1) {
                lookupResults.style.display = 'none';
                lookupResults.innerHTML = '';
                return;
            }
            lookupTimer = setTimeout(() => {
                fetch('{{ route("PLAYER_SEARCH") }}?q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json' }
                })
                    .then((res) => res.json())
                    .then((payload) => {
                        const players = payload.players || [];
                        if (!players.length) {
                            lookupResults.innerHTML = '<a href="#">No players found</a>';
                            lookupResults.style.display = 'block';
                            return;
                        }
                        lookupResults.innerHTML = players.map((player) => (
                            '<a href="/web/verified/players/' + player.player_id + '">' +
                            (player.last_player_name || 'Unknown') +
                            ' <small>#' + player.player_id + '</small></a>'
                        )).join('');
                        lookupResults.style.display = 'block';
                    })
                    .catch(() => {
                        lookupResults.style.display = 'none';
                    });
            }, 180);
        });
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.sidebar-search')) {
                lookupResults.style.display = 'none';
            }
        });
    }
});
</script>
