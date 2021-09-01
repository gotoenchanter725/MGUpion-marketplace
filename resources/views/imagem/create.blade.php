@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-6 col-md-8">
        <div class="card">
            <h4 class="card-header">
                Novo
            </h4>
            <div class="card-block">
                {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-principal', 'route' => 'imagem.store']) !!}
                    @include('errors.form_error')
                    @include('imagem.form')
                {!! Form::close() !!}   
            </div>
        </div>
    </div>
</div>
@stop