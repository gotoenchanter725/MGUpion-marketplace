@include('layouts.includes.header_start')
@include('layouts.includes.header_end')

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page-quiosque" id="content-page">
    <div class="content">
		<div class="container">
            @yield('content')
        </div>
    </div> <!-- container -->
</div> <!-- content -->
<!-- End content-page -->


@include('layouts.includes.footer_end')
