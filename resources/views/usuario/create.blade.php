@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h4 class="card-header">
                Novo
            </h4>
            <div class="card-block">
            {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-usuario', 'route' => 'usuario.store']) !!}
                @include('errors.form_error')
                @include('usuario.form')
            {!! Form::close() !!} 
            </div>
        </div>
    </div>
</div>
@stop