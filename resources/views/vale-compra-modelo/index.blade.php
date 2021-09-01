@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codvalecompramodelo" class="control-label">#</label>
                        {!! Form::number('codvalecompramodelo', null, ['class'=> 'form-control', 'id'=>'codvalecompramodelo', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="modelo" class="control-label">Modelo</label>
                        {!! Form::text('modelo', null, ['class'=> 'form-control', 'id'=>'modelo', 'placeholder'=>'Modelo']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codpessoafavorecido" class="control-label">Favorecido</label>
                        {!! Form::select2Pessoa('codpessoafavorecido', null, ['class' => 'form-control', 'placeholder' => 'Favorecido']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="ano" class="control-label">Ano</label>
                        {!! Form::text('ano', null, ['class'=> 'form-control', 'id'=>'ano', 'placeholder'=>'Ano']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="inativo" class="control-label">Ativos</label>
                        {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                    </div>
                </div>
                <div class="clearfix"></div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>

<div class='card'>
    <div class='card-block table-responsive'>
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' =>                                                          ['URL', 'Inativo Desde', '#', 'Modelo', 'Total', 'Favorecido', 'Ano', 'Turma', 'Observações' ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('vale-compra-modelo/datatable'), 'order' => $filtro['order'], 'filtros' => ['codvalecompramodelo'=>'codvalecompramodelo', 'inativo', 'modelo', 'codpessoafavorecido', 'ano' ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
