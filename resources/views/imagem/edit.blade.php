@extends('layouts.default')
@section('content')
<div class='row'>
    <div class="col-lg-6 col-md-8">
        <div class="card">
            <h4 class="card-header">Alterar</h4>
            <div class="card-block">
                {!! Form::model($model, ['method' => 'PATCH', 'id' => 'form-principal', 'action' => ['ImagemController@update', $model->codimagem] ]) !!}
                    @include('errors.form_error')
                    @include('imagem.form')
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