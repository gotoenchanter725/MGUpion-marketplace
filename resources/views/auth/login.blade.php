@extends('layouts.app')

@section('content')
<div class="m-t-10 p-20">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form class="m-t-20" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="form-group row">
            <div class="col-xs-12">
                <input id="usuario" type="text" class="form-control{{ $errors->has('email') ? ' has-error' : '' }}" name="usuario"  placeholder="Usuário" required autofocus>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-xs-12">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" name="password" placeholder="Senha" required>
            </div>
        </div>

        <div class="form-group text-center row m-t-10">
            <div class="col-xs-12">
                <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Entrar</button>
            </div>
        </div>
    </form>
</div>
@endsection
