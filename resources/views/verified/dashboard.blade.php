
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
            <div class="row">
                <div class="col col-12 text-end h-auto">
                    <button class="btn btn-success"> <!-- TODO Open modal to add a widget via selection? -->
                        <!-- This button should only be active in customize mode maybe?? -->
                        <span class="fa fa-plus-circle"> Add</span>
                    </button>
                </div>
            </div>
            <div class="row flex-grow-1">
                <div class="col col-12">
                    <div class="grid-stack">
                        @foreach($data['widgetData'] as $widget)
                            <div class="grid-stack-item" gs-y="{{$widget['row']}}" gs-x="{{$widget['col']}}"
                                gs-width="{{$widget['size_x']}}" gs-height="{{$widget['size_y']}}">
                                <div class="container-fluid h-100 px-0 grid-stack-item-content">
                                    <div class="d-flex flex-column h-100">
                                        <div class="row bg-zap mx-0 widget-header">
                                            <div class="col-12 text-white">
                                                {{ config('widget.WIDGET_NAMES_FROM_TYPE')[$widget['widget_type']] }}
                                            </div>
                                        </div>
                                        <div class="row mx-0 bg-white flex-grow-1">
                                            <div class="col-12 text-dark justify-content-center align-middle d-flex align-items-center">
                                                @include("_widgets." . $widget['widget_type'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('_partials._html_footer')
</div>
<script>
    $(function () { //DOM Ready
        GridStack.init(
            {
            }
        );
    });
</script>
</body>
</html>
