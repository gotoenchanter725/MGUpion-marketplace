<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ERP MG Papelaria">
    <meta name="author" content="MG Papelaria">
    <link rel="shortcut icon" href="{{ URL::asset('public/assets/images/favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $bc->page }}</title>

    <!-- jQuery  -->
    <script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script>
    
    <!-- Tether for Bootstrap -->
    <script src="{{ URL::asset('public/assets/js/tether.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/detect.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/fastclick.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/jquery.blockUI.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/waves.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/jquery.nicescroll.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ URL::asset('public/assets/js/jquery.fullscreen-min.js') }}"></script>

    <!-- Toastr Notification  -->
    <link href="{{ URL::asset('public/assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ URL::asset('public/assets/plugins/toastr/toastr.min.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ URL::asset('public/assets/plugins/bootstrap-sweetalert/sweet-alert.min.js') }}"></script>
    <link href="{{ URL::asset('public/assets/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Switchery -->
    <script src="{{ URL::asset('public/assets/plugins/switchery/switchery.min.js') }}"></script>
    <link href="{{ URL::asset('public/assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />

    <!-- Select2 -->
    <link href="{{ URL::asset('public/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/assets/plugins/select2/theme/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <script src="{{ URL::asset('public/assets/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('public/assets/plugins/select2/js/i18n/pt-BR.js') }}" type="text/javascript"></script>
    <!-- MG -->
    <link href="{{ URL::asset('public/css/mglara.css') }}" rel="stylesheet" />
    <script src="{{ URL::asset('public/js/mglara.js') }}"></script>

    @yield('inscript')