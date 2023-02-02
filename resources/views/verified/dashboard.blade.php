<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header')
    <body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        <div class="row">
            <div class="col col-auto px-0">
                @include('_partials._sidebar')
            </div>
            <div class="col col-auto page d-flex">
                <div class="gridster flex-grow-1">
                    <ul>
                        <li data-row="1" data-col="1" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>
                        <li data-row="2" data-col="1" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>
                        <li data-row="3" data-col="1" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>

                        <li data-row="1" data-col="2" data-sizex="2" data-sizey="1">@include('_partials.widget_placeholder')</li>
                        <li data-row="2" data-col="2" data-sizex="2" data-sizey="2">@include('_partials.widget_placeholder')</li>

                        <li data-row="1" data-col="4" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>
                        <li data-row="2" data-col="4" data-sizex="2" data-sizey="1">@include('_partials.widget_placeholder')</li>
                        <li data-row="3" data-col="4" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>

                        <li data-row="1" data-col="5" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>
                        <li data-row="3" data-col="5" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>

                        <li data-row="1" data-col="6" data-sizex="1" data-sizey="1">@include('_partials.widget_placeholder')</li>
                        <li data-row="2" data-col="12" data-sizex="1" data-sizey="2">@include('_partials.widget_placeholder')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        @include('_partials._html_footer')
    <script>
        $(function() { //DOM Ready

            $(".gridster ul").gridster({
                widget_base_dimensions: ['auto', 100],
                widget_margins: [5, 5],
                min_cols: 1,
                max_cols: 12,
                autogenerate_stylesheet: true,
                resize: {
                    enabled: true
                },
                shift_widgets_up: false,
                shift_larger_widgets_down: false,
                collision: {
                    wait_for_mouseup: true
                }
            }).data('gridster');

        });
    </script>
    </body>
</html>
