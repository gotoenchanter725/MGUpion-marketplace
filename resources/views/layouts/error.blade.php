@include('layouts.includes.header_account')

<div class="ex-page-content text-xs-center">
    @yield('content')
    <h4><a href="javascript:window.history.back();">Clique para retornar!</a></h4>
</div>

@include('layouts.includes.footer_account')