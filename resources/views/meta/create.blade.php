@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h4 class="card-header">Nova</h4>
            <div class="card-block">
            {!! Form::model($model, [
                'method' => 'POST', 
                'class' => 'form-horizontal', 
                'id' => 'form-meta', 
                'route' => [
                    'meta.store', 
                    'alterar'=> isset(Request::all()['alterar']) ? Request::all()['alterar'] : null
                ] 
            ]) !!}
                @include('errors.form_error')
                @include('meta.form', ['submitTextButton' => 'Salvar'])
             {!! Form::close() !!}
             <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>
@stop