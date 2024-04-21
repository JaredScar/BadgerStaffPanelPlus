<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \Illuminate\Support\Facades\Session::get("server_name") }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/css/bootstrap5-toggle.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.0/css/fixedHeader.dataTables.min.css" />
    <!-- Include GridStack CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.0/gridstack.min.css" integrity="sha512-GEMqAs1Ai2iirp6QhD4N5Qt7tDn1RGgF1CY2xp57LrX3vqTDehSC88IOFewV/L84htjbN7aazcK10FyzH+eXSA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Include air-datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.1.0/css/datepicker.min.css" integrity="sha512-mEt69CXdH41K9qBj/MZ54fAyDVV+9Lq/3ElOWl63qHrDUUV4dfYO+8Wou9/fryyc0eplS63V3ICYbDPQ+e0vBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/' . $data['css_path'] . '.css') }}" />
</head>
