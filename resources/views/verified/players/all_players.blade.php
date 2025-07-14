<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
    <body class="background-sizing gta-bg1">
        <div class="container-fluid master-contain">
            @include('_partials._toast')
            <div class="row">
                <div class="col col-auto px-0">
                    @include('_partials._sidebar')
                </div>
                <div class="col col-auto flex-fill page-contain">
                    <div class="content-wrapper">
                        <!-- Header Section -->
                        <div class="page-header d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h1 class="page-title">
                                    <i class="fas fa-database me-2"></i>
                                    All Players
                                </h1>
                                <p class="page-description">Complete database of all registered players</p>
                            </div>
                        </div>

                        <!-- Search and Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="search-container">
                                    <input type="text" class="form-control" id="allPlayersSearch" placeholder="Search players...">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="allPlayersFilter">
                                    <option value="">All Players</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                    <option value="high_trust">High Trust (80+)</option>
                                    <option value="low_trust">Low Trust (<50)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Include the all players widget -->
                        @include('_widgets.players.widget_all_players')
                    </div>
                </div>
            </div>
        </div>
        @include('_partials._html_footer')
    </body>
</html>

<style>
/* All Players specific styles */
.content-wrapper {
    padding: 20px;
}

.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.page-title i {
    color: #fd7e14;
    margin-right: 10px;
}

.page-description {
    color: #6c757d;
    margin: 0;
    font-size: 16px;
}

.search-container {
    position: relative;
}

.search-container .form-control {
    padding-left: 40px;
    border-radius: 8px;
    border: 2px solid #e3e6f0;
    height: 45px;
}

.search-container .form-control:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
}

.form-select {
    border-radius: 8px;
    border: 2px solid #e3e6f0;
    height: 45px;
    font-weight: 500;
}

.form-select:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.master-contain {
    padding: 0;
    margin: 0;
    height: 100vh;
    overflow: hidden;
}

.page-contain {
    height: 100vh;
    overflow-y: auto;
    padding: 0;
}
</style> 