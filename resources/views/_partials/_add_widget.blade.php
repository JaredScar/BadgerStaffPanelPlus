<div class="container-fluid bg-custom-dark-full p-3 d-none" id="add_widget_screen">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="row justify-content-center">
                <!-- Bans -->
                @if (!$data['widgetData']->contains('widget_type', 'records.widget_bans'))
                <div class="col-4 mb-3">
                    <div class="card w-100 h-100 p-2">
                        <i class="fa-solid fa-gavel fs-1 text-center"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Bans Widget</h5>
                            <p class="card-text">A widget that contains all the information about bans within the server.</p>
                            <div class="mt-auto d-flex justify-content-center">
                                <form method="POST" action="dashboard/add_widget">
                                    @csrf
                                    <button class="btn btn-success" name="widget_type" value="widget_bans" type="submit">Add Widget</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Commends -->
                @if (!$data['widgetData']->contains('widget_type', 'records.widget_commends'))
                <div class="col-4 mb-3">
                    <div class="card w-100 h-100 p-2">
                        <i class="fa-solid fa-comment-dots fs-1 text-center"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Commends Widget</h5>
                            <p class="card-text">A widget that contains all the information about commends within the server.</p>
                            <div class="mt-auto d-flex justify-content-center">
                                <form method="POST" action="dashboard/add_widget">
                                    @csrf
                                    <button class="btn btn-success" name="widget_type" value="widget_commends" type="submit">Add Widget</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Kicks -->
                @if (!$data['widgetData']->contains('widget_type', 'records.widget_kicks'))
                <div class="col-4 mb-3">
                    <div class="card w-100 h-100 p-2">
                        <i class="fa-solid fa-shoe-prints fs-1 text-center"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Kicks Widget</h5>
                            <p class="card-text">A widget that contains all the information about kicks within the server.</p>
                            <div class="mt-auto d-flex justify-content-center">
                                <form method="POST" action="dashboard/add_widget">
                                    @csrf
                                    <button class="btn btn-success" name="widget_type" value="widget_kicks" type="submit">Add Widget</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Warns -->
                @if (!$data['widgetData']->contains('widget_type', 'records.widget_warns'))
                <div class="col-4 mb-3">
                    <div class="card w-100 h-100 p-2">
                        <i class="fa-solid fa-person-harassing fs-1 text-center"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Warns Widget</h5>
                            <p class="card-text">A widget that contains all the information about warns within the server.</p>
                            <div class="mt-auto d-flex justify-content-center">
                                <form method="POST" action="dashboard/add_widget">
                                    @csrf
                                    <button class="btn btn-success" name="widget_type" value="widget_warns" type="submit">Add Widget</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Notes -->
                @if (!$data['widgetData']->contains('widget_type', 'records.widget_notes'))
                <div class="col-4 mb-3">
                    <div class="card w-100 h-100 p-2">
                        <i class="fa-solid fa-pen-to-square fs-1 text-center"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Notes Widget</h5>
                            <p class="card-text">A widget that contains all the information about notes within the server.</p>
                            <div class="mt-auto d-flex justify-content-center">
                                <form method="POST" action="dashboard/add_widget">
                                    @csrf
                                    <button class="btn btn-success" name="widget_type" value="widget_notes" type="submit">Add Widget</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Records -->
                @if (!$data['widgetData']->contains('widget_type', 'records.widget_records'))
                <div class="col-4 mb-3">
                    <div class="card w-100 h-100 p-2">
                        <i class="fa-solid fa-clipboard fs-1 text-center"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Records Widget</h5>
                            <p class="card-text">A widget that contains all the information about records within the server.</p>
                            <div class="mt-auto d-flex justify-content-center">
                                <form method="POST" action="dashboard/add_widget">
                                    @csrf
                                    <button class="btn btn-success" name="widget_type" value="widget_records" type="submit">Add Widget</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Trustscores -->
                @if (!$data['widgetData']->contains('widget_type', 'records.widget_trustscores'))
                <div class="col-4 mb-3">
                    <div class="card w-100 h-100 p-2">
                        <i class="fa-solid fa-circle-check fs-1 text-center"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Trustscores Widget</h5>
                            <p class="card-text">A widget that contains all the information about trustscores within the server.</p>
                            <div class="mt-auto d-flex justify-content-center">
                                <form method="POST" action="dashboard/add_widget">
                                    @csrf
                                    <button class="btn btn-success" name="widget_type" value="widget_trustscores" type="submit">Add Widget</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
