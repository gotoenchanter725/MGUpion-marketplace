<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('public/assets/images/favicon.ico') }}">

        <!-- App title -->
        <title>Uplon - Responsive Admin Dashboard Template</title>

        <!-- App CSS -->
        <link href="{{ URL::asset('public/assets/css/style.css') }}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js' doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js') }}/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="{{ URL::asset('public/assets/js/modernizr.min.js') }}"></script>

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    </head>


    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">

            <div class="account-bg">
                <div class="card-box m-b-0">
                    <!--
                    <div class="text-xs-center m-t-20">
                        <a href="index.html" class="logo">
                            <i class="zmdi zmdi-group-work icon-c-logo"></i>
                            <span>Autenticação</span>
                        </a>
                    </div>
                    -->
                    @yield('content')

                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- end card-box-->

        </div>
        <!-- end wrapper page -->


        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <!-- jQuery  -->
        <script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('public/assets/js/tether.min.js') }}"></script><!-- Tether for Bootstrap -->
        <script src="{{ URL::asset('public/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('public/assets/js/waves.js') }}"></script>
        <script src="{{ URL::asset('public/assets/js/jquery.nicescroll.js') }}"></script>
        <script src="{{ URL::asset('public/assets/plugins/switchery/switchery.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ URL::asset('public/assets/js/jquery.core.js') }}"></script>
        <script src="{{ URL::asset('public/assets/js/jquery.app.js') }}"></script>

    </body>
</html>