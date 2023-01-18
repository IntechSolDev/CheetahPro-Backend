<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{str_replace("_"," ",config('app.name'))}}</title>

    <!-- Bootstrap core CSS-->
    <link href="{{ asset('public/admin/admin-template/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('public/admin/admin-template/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="{{ asset('public/admin/admin-template/vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('public/admin/admin-template/css/sb-admin.css')}}" rel="stylesheet">
    <link href="{{ asset('public/admin/admin-template/css/custom.css')}}" rel="stylesheet">
    <!--DataTable css -->
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

</head>
