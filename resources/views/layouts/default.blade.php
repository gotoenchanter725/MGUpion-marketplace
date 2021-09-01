@include('layouts.includes.header_start')
@include('layouts.includes.header_end')
@include('layouts.includes.topbar')
@include('layouts.includes.leftbar')

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page" id="content-page">
    <div class="content">
		<div class="container">
		<div class="row">
		    <div class="col-xs-12">
		        <div class="page-title-box">
		            <h4 class="page-title">
                        {{ $bc->header }}
                        <small class='text-danger'>
                            @yield('inactive')
                        </small>
                        <div class="btn-group " role="group" aria-label="Controles">
                            &nbsp;
                            @yield ('buttons')
                        </div>
                    </h4>
		            <ol class="breadcrumb p-0">
		            	@foreach($bc->breadcrumbs as $item)
		            		@if (empty($item->url))
                                <li class="active">
                                    {{ $item->label }}
                                </li>
		            		@else
                                <li>
                                    <a href="{{ $item->url }}"  >{{ $item->label }}</a>
                                </li>
		            		@endif
		            	@endforeach
		            </ol>
                    
		            <div class="clearfix"></div>
		        </div>
		    </div>
		</div>
                @yield('content')
        </div>
    </div> <!-- container -->
</div> <!-- content -->
<!-- End content-page -->

@include('layouts.includes.footer_start')
@include('layouts.includes.footer_end')
