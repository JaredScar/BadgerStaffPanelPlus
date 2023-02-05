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
    });
</script>
</body>
</html>
