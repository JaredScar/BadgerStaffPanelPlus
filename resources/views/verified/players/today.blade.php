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
                                    <i class="fas fa-users me-2"></i>
                                    Players Today
                                </h1>
                                <p class="page-description">5 players have joined today</p>
                            </div>
                        </div>

                        <!-- Include the players widget -->
                        @include('_widgets.players.widget_players')
                    </div>
                </div>
            </div>
        </div>

        @include('_partials._html_footer')
    </body>
</html>
