
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
                    <button class="btn btn-success"> <!-- TODO This button will pop up a modal to add a widget -->
                        <!-- This button should only be active in customize mode maybe?? -->
                        <span class="fa fa-plus-circle"> Add</span>
                    </button>
                </div>
            </div>
            <div class="gridster flex-grow-1 row">
                <div class="col col-12">
                    <ul>
                        @foreach($data['widgetData'] as $widget)
                            <li data-row="{{$widget['row']}}" data-col="{{$widget['col']}}"
                                data-sizex="{{$widget['size_x']}}" data-sizey="{{$widget['size_y']}}">
                                <div class="container-fluid h-100 px-0">
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
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('_partials._html_footer')
</div>
<script>
    $(function () { //DOM Ready
        const gridster = $(".gridster ul").gridster({
            widget_base_dimensions: ['auto', 100],
            widget_margins: [5, 5],
            avoid_overlapped_widgets: true, // Don't allow widgets loaded from the DOM to overlap. This is helpful if you're loading widget positions form the database, and they might be inconsistent.
            max_cols: 12,
            autogenerate_stylesheet: true, // If true, all the CSS required to position all widgets in their respective columns and rows will be generated automatically and injected to the <head> of the document.
            resize: {
                enabled: true
            },
            shift_widgets_up: false,
            shift_larger_widgets_down: false,
            collision: {
                wait_for_mouseup: true
            }
        }).data('gridster');
        gridster.disable();
    });
</script>
</body>
</html>
