@extends('layouts.default')
@section('content')
<div class="row">
    {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-principal', 'route' => 'cheque-repasse.store']) !!}
        @include('errors.form_error')
        @include('cheque-repasse.form')
    {!! Form::close() !!}
</div>
@stop