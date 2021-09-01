@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="codvalecompra" class="control-label">#</label>
                        {!! Form::number('codvalecompra', null, ['class'=> 'form-control', 'id'=>'codvalecompra', 'step'=>1, 'min'=>1]) !!}
                    </div>
                    <div class="form-group">
                        <label for="codvalecompramodelo" class="control-label">Modelo</label>
                        {!! Form::select2ValeCompraModelo('codvalecompramodelo', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="codpessoafavorecido" class="control-label">Favorecido</label>
                        <div>{!! Form::select2Pessoa('codpessoafavorecido', null, ['class' => 'form-control select2', 'placeholder' => 'Favorecido', 'id'=>'codpessoafavorecido']) !!}</div>
                    </div>
                    <div class="form-group">
                        <label for="inativo" class="control-label">Ativos</label>
                        {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="codpessoa" class="control-label">Pessoa</label>
                        {!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'placeholder' => 'Pessoa']) !!}
                    </div>
                    <div class="form-group">
                        <label for="aluno" class="control-label">Aluno</label>
                        {!! Form::text('aluno', null, ['class'=> 'form-control', 'id'=>'aluno']) !!}
                    </div>
                    <div class="form-group">
                        <label for="turma" class="control-label">Turma</label>
                        {!! Form::text('turma', null, ['class'=> 'form-control', 'id'=>'turma']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="codusuariocriacao" class="control-label">Usuário</label>
                        {!! Form::select2Usuario('codusuariocriacao', null, ['class'=> 'form-control', 'id' => 'codusuariocriacao']) !!}
                    </div>
                    <div class="form-group">
                        <label for="criacao_de" class="control-label">De</label>
                        {!! Form::datetimeLocal('criacao_de', null, ['class'=> 'form-control', 'id' => 'criacao_de']) !!}
                    </div>
                    <div class="form-group">
                        <label for="criacao_ate" class="control-label">De</label>
                        {!! Form::datetimeLocal('criacao_ate', null, ['class'=> 'form-control', 'id' => 'criacao_ate']) !!}
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Aluno', 'Turma', 'Total', 'Pessoa', 'Favorecido', 'Modelo', 'Data', 'Usuário']])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('vale-compra/datatable'), 'order' => $filtro['order'], 'filtros' => ['codvalecompra', 'codvalecompra', 'inativo', 'codvalecompramodelo', 'codpessoafavorecido', 'codpessoa', 'aluno', 'turma', 'codusuariocriacao', 'criacao_de', 'criacao_ate' ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
