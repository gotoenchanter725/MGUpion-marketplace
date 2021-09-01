@extends('layouts.error')
@section('content')

<div class="m-t-50 text-error text-danger shadow">500</div>
<h3 class="text-uppercase text-danger font-600">Falha</h3>
<p class="m-t-30">
    @if (empty($mensagem))
        Erro interno do servidor na execução!
    @else
        {{ $mensagem }}
    @endif
</p>

@stop