@extends('layouts.error')
@section('content')

<div class="m-t-50 text-error text-danger shadow">403</div>
<h3 class="text-uppercase text-danger font-600">Permissão Negada</h3>
<p class="m-t-30">
    @if (empty($mensagem))
        Você não tem permissão de acesso à este recurso!
    @else
        {{ $mensagem }}
    @endif    
</p>

@stop