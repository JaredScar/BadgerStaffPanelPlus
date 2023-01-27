<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header')
    <style>
        .page {
            background-color: rgba(241, 241, 241, 0.85);
            display: flex;
            width: 100vw;
            flex: 1;
            margin: 20px 20px 95px 20px;
            border-radius: 15px;
        }
    </style>
    <body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        <div class="row">
            <div class="col col-auto px-0">
                @include('_partials._sidebar')
            </div>
            <div class="col col-auto page">
            </div>
        </div>
    </div>
        @include('_partials._html_footer')
    </body>
</html>
