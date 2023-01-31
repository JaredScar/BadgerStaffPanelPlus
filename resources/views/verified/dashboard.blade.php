<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header')
    <body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        <div class="row">
            <div class="col col-auto px-0">
                @include('_partials._sidebar')
            </div>
            <div class="col col-auto page">
                <div class="gridster bg-danger">
                    <ul>
                        <li data-row="1" data-col="1" data-sizex="1" data-sizey="1">Content</li>
                        <li data-row="2" data-col="1" data-sizex="1" data-sizey="1"></li>
                        <li data-row="3" data-col="1" data-sizex="1" data-sizey="1"></li>

                        <li data-row="1" data-col="2" data-sizex="2" data-sizey="1"></li>
                        <li data-row="2" data-col="2" data-sizex="2" data-sizey="2"></li>

                        <li data-row="1" data-col="4" data-sizex="1" data-sizey="1"></li>
                        <li data-row="2" data-col="4" data-sizex="2" data-sizey="1"></li>
                        <li data-row="3" data-col="4" data-sizex="1" data-sizey="1"></li>

                        <li data-row="1" data-col="5" data-sizex="1" data-sizey="1"></li>
                        <li data-row="3" data-col="5" data-sizex="1" data-sizey="1"></li>

                        <li data-row="1" data-col="6" data-sizex="1" data-sizey="1"></li>
                        <li data-row="2" data-col="12" data-sizex="1" data-sizey="2"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        @include('_partials._html_footer')
    <script>
        $(function(){ //DOM Ready

            $(".gridster ul").gridster({
                widget_margins: [5, 5],
                widget_base_dimensions: [100, 100],
                resize: {
                    enabled: true
                },
                shift_widgets_up: false,
                shift_larger_widgets_down: false,
                collision: {
                    wait_for_mouseup: true
                }
            });

        });
    </script>
    </body>
</html>
