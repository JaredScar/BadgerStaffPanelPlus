
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
            <div class="row flex-grow-1">
                <div class="col col-12">
                    <div class="grid-stack">
                        @foreach($data['widgetData'] as $widget)
                            <div class="grid-stack-item" gs-y="{{$widget['row']}}" gs-x="{{$widget['col']}}"
                                gs-width="{{$widget['size_x']}}" gs-height="{{$widget['size_y']}}" data-widgetType="{{$widget['widget_type']}}">
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
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    $(function () { //DOM Ready
        const grid = GridStack.init(
            {
                float: true,
                alwaysShowResizeHandle: 'mobile',
                cellHeight: 50,
                disableDrag: true,
                disableResize: true,
                margin: 0,
                removable: true,
                removeTimeout: 3000
            }
        );
        let customize = false;
        $('#customize_val').on('change', () => {
            grid.enableMove(!customize);
            grid.enableResize(!customize);
            customize = !customize;
            if (!customize) {
                const widgetDatas = [];
                for (let gridItem of grid.getGridItems()) {
                    const gridStackNode = gridItem?.gridstackNode;
                    const gridEl = gridStackNode?.el;
                    const widgetType = gridEl?.dataset?.widgettype;
                    if (widgetType && gridStackNode) {
                        // Valid widget type... We need to save
                        const h = gridStackNode.h;
                        const w = gridStackNode.w;
                        const x = gridStackNode.x;
                        const y = gridStackNode.y;
                        const widgetData = {
                            widgetType: widgetType,
                            x: x,
                            y: y,
                            h: h,
                            w: w
                        };
                        widgetDatas.push(widgetData);
                    }
                }
                const opts = {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(widgetDatas)
                };
                fetch('dashboard/save', opts).then((resp) => {
                    console.log("Response from saving =>", resp);
                });
            }
        });
    });
</script>
</body>
</html>
