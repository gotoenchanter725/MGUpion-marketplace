@extends('layouts.default')
@section('content')

<div class="card-box">
  <h1>
    Seja bem Vindo 
    <a href="{{ url('usuario', Auth::user()->codusuario) }}">
    @if (!empty(Auth::user()->codpessoa))
        {{ Auth::user()->Pessoa->fantasia }}
    @else
        {{ Auth::user()->usuario }}
    @endif
    </a>
    !
  </h1>
  <hr>
  Seu último acesso foi {{ formataData(Auth::user()->ultimoacesso, 'E') }}!
</div>

@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop