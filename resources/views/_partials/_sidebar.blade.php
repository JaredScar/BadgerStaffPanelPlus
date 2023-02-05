<style>
    .main-sidebar {
        min-height: 100vh;
        height: 100%;
    }

    main {
        display: flex;
        flex-wrap: nowrap;
        height: 100vh;
        max-height: 100vh;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .btn-toggle, .sidebar-btn, .btn-toggle-icons {
        display: inline-flex;
        align-items: center;
        padding: .25rem .5rem;
        font-weight: 600;
        color: rgb(255, 255, 255);
        background-color: transparent;
        border: 0;
    }
    .btn-toggle-icons {
        display: inline-flex;
        align-items: center;
        padding: .25rem 0;
        font-weight: 600;
        color: rgb(255, 255, 255);
        background-color: transparent;
        border: 0;
    }
    .btn-toggle:hover,
    .sidebar-btn:hover
    {
        color: rgb(255, 255, 255);
        background-color: #57BC54;
    }
    .no-color-hover:hover {
        background-color: initial !important;
    }

    .btn-toggle-icons::before {
        width: 1.25em;
        line-height: 0;
        font-family: "Font Awesome 5 Free";
        content: '\f0da';
        transition: transform .35s ease;
        transform-origin: .5em 50%;
    }

    .btn-toggle[aria-expanded="true"] {
        color: rgba(255, 255, 255, 0.85);
    }
    .btn-toggle[aria-expanded="true"] .btn-toggle-icons::before {
        transform: rotate(90deg);
    }

    .btn-toggle-nav a {
        display: inline-flex;
        padding: .1875rem .5rem;
        margin-top: .125rem;
        margin-left: 1.25rem;
        text-decoration: none;
    }
    .btn-toggle-nav a:hover {
        background-color: rgba(87, 188, 84, 0.61);
    }

    .scrollarea {
        overflow-y: auto;
    }

    .fw-semibold { font-weight: 600; }
    .lh-tight { line-height: 1.25; }
    .toggle-btn .btn-primary {
        background-color: #57BC54;
    }
    .toggle-btn .btn-secondary {
        background-color: black;
    }
</style>
<div class="flex-shrink-0 p-3 bg-custom-dark-80a main-sidebar d-inline-block" style="width: 240px;">
    <a href="/" class="d-flex align-items-center pb-3 mb-3 link-light text-decoration-none border-bottom">
        <span class="fs-5 fw-semibold">BadgerStaffPanel+</span>
    </a>
    <ul class="list-unstyled ps-0">
        <div class="d-block pb-2 link-light text-decoration-none sidebar-btn no-color-hover">
            <span class="sidebar-link"><i class="fa-solid fa-pen-ruler"></i> Customize <span class="float-end toggle-btn"><input id="customize_val" type="checkbox" data-toggle="toggle" data-size="xs" /></span></span>
        </div>
        <li class="mb-1">
            <a href="/verified/dashboard" class="btn sidebar-btn align-items-center rounded">
                <span class="sidebar-link"><i class="fa-brands fa-fort-awesome"></i> Dashboard</span>
            </a>
        </li>
        <li class="mb-1">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                <span class="sidebar-link"><i class="fa-solid fa-compact-disc"></i><span class="btn-toggle-icons">Records</span></span>
            </button>
            <div class="collapse" id="dashboard-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/verified/records/commends" class="link-light rounded">Commends</a></li>
                    <li><a href="/verified/records/warns" class="link-light rounded">Warns</a></li>
                    <li><a href="/verified/records/kicks" class="link-light rounded">Kicks</a></li>
                    <li><a href="/verified/records/bans" class="link-light rounded">Bans</a></li>
                    <li><a href="/verified/records/trustscores" class="link-light rounded">TrustScores</a></li>
                    <li><a href="/verified/records" class="link-light rounded">All Records</a></li>
                </ul>
            </div>
        </li>
        <li class="mb-1">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                <span class="sidebar-link"><i class="fa-solid fa-users"></i><span class="btn-toggle-icons">Players</span></span>
            </button>
            <div class="collapse" id="orders-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/verified/players/today" class="link-light rounded">Players Today</a></li>
                    <li><a href="/verified/players/week" class="link-light rounded">Weekly Players</a></li>
                    <li><a href="/verified/players/month" class="link-light rounded">Monthly Players</a></li>
                    <li><a href="/verified/players" class="link-light rounded">All Players</a></li>
                </ul>
            </div>
        </li>
        <li class="border-top my-3"></li>
        <li class="mb-1">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                <span class="sidebar-link"><i class="fa-solid fa-wrench"></i><span class="btn-toggle-icons">Management</span></span>
            </button>
            <div class="collapse" id="account-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/verified/management/manage_staff" class="link-light rounded">Manage Staff</a></li>
                    <li><a href="/verified/management/settings" class="link-light rounded">Settings</a></li>
                    <li><a href="/verified/signout" class="link-light rounded">Sign out</a></li>
                </ul>
            </div>
        </li>
    </ul>
</div>
