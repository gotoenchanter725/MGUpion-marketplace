@extends('layouts.default')
@section('content')
<div class='row'>
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">Alterar</h4>
            <div class="card-block">
                {!! Form::model($model, ['method' => 'PATCH', 'id' => 'form-principal', 'action' => ['UnidadeMedidaController@update', $model->codunidademedida] ]) !!}
                    @include('errors.form_error')
                    @include('unidade-medida.form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@stop