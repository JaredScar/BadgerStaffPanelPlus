
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
            <div class="col col-auto page d-flex position-relative">
                <button class="btn btn-primary position-absolute z-2 opacity-75 end-0 me-2 d-none" id="add_widget_btn"><i class="fa-solid fa-circle-plus"></i></button>
                @include('_partials._add_widget')
                <div class="row flex-grow-1">
                    <div class="col col-12">
                        <div class="grid-stack">
                            @foreach($data['widgetData'] as $widget)
                                <div class="grid-stack-item" gs-y="{{$widget['row']}}" gs-x="{{$widget['col']}}"
                                     gs-w="{{$widget['size_x']}}" gs-h="{{$widget['size_y']}}"
                                     data-widgetType="{{$widget['widget_type']}}"
                                     data-widgetId="{{$widget['widget_id']}}"
                                >
                                    <div class="container-fluid h-100 px-0 grid-stack-item-content">
                                        <div class="d-flex flex-column h-100">
                                            <div class="row bg-zap mx-0 widget-header">
                                                <div class="col-10 text-white">
                                                    {{ config('widget.WIDGET_NAMES_FROM_TYPE')[$widget['widget_type']] }}
                                                </div>
                                                <div class="col-2 text-danger widget-actions text-end d-none">
                                                    <form action="dashboard/remove_widget" method="post">
                                                        <input type="hidden" name="widget_id" value="{{$widget['widget_id']}}" />
                                                        <i class="fa-solid fa-trash"></i>
                                                    </form>
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
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const grid = GridStack.init(
                {
                    float: true,
                    alwaysShowResizeHandle: 'mobile',
                    cellHeight: 50,
                    disableDrag: true,
                    disableResize: true,
                    margin: 0
                }
            );
            let customize = false;
            $('#add_widget_btn').on('click', () => {
                $('#add_widget_screen').removeClass('d-none');
                $('#add_widget_btn').addClass('d-none');
            });
            $('#customize_val').on('change', () => {
                grid.enableMove(!customize);
                grid.enableResize(!customize);
                customize = !customize;
                if (!customize) {
                    $('#add_widget_screen').addClass('d-none');
                    $('#add_widget_btn').addClass('d-none');
                    $('.widget-actions').each((index, elm) => {
                        $(elm).addClass('d-none');
                    });
                    const widgetDatas = [];
                    for (let gridItem of grid.getGridItems()) {
                        const gridStackNode = gridItem?.gridstackNode;
                        const gridEl = gridStackNode?.el;
                        const widgetType = gridEl?.dataset?.widgettype;
                        const widgetId = gridEl?.dataset?.widgetid;
                        if (widgetType && gridStackNode) {
                            // Valid widget type... We need to save
                            const h = gridStackNode.h;
                            const w = gridStackNode.w;
                            const x = gridStackNode.x;
                            const y = gridStackNode.y;
                            const widgetData = {
                                widgetType: widgetType,
                                widgetId: widgetId,
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
                        if (resp.status === 200) {
                            // Successfully saved...
                        } else {
                            // Unsuccessful save, error encountered...
                            showToast('error_toast', "Saving was unsuccessful...");
                        }
                    });
                } else {
                    // Customize was turned on...
                    $('#add_widget_btn').removeClass('d-none');
                    $('.widget-actions').each((index, elm) => {
                        $(elm).removeClass('d-none');
                    });
                }
            });
        });
    </script>
</body>
</html>
