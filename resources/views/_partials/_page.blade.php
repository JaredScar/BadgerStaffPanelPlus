<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
    <body class="background-sizing gta-bg1">
        @include('_partials._toast')
        @include('_partials._sidebar')
        
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="full-page-header d-flex align-items-center">
                            MANAGEMENT
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="subpage">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('_partials._html_footer')
    </body>
</html>
