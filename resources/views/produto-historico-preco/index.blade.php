@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="codprodutohistoricopreco" class="control-label">#</label>
                            {!! Form::number('codprodutohistoricopreco', null, ['class'=> 'form-control', 'id'=>'codprodutohistoricopreco', 'step'=>1, 'min'=>1]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="produto" class="control-label">Produto</label>
                            {!! Form::text('produto', null, ['class'=> 'form-control', 'id'=>'produto']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="referencia" class="control-label">Referência</label>
                            {!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="codmarca" class="control-label">Marca</label>
                            {!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'placeholder'=>'Marca']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="codusuario" class="control-label">Usuário</label>
                            {!! Form::select2Usuario('codusuario', null, ['class'=> 'form-control', 'id' => 'codusuario', 'placeholder' => 'Usuário']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="inativo" class="control-label">Ativos</label>
                            {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="alteracao_de" class="control-label">De</label>
                            {!! Form::datetimeLocal('alteracao_de', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_de', 'placeholder' => 'De', 'style'=>'margin-right:10px']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="alteracao_ate" class="control-label">Até</label>
                            {!! Form::datetimeLocal('alteracao_ate', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_ate', 'placeholder' => 'Até']) !!}                        
                        </div>
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL','',  '#', 'Produto', 'Embalagem', 'Ref', 'Marca', 'Preço','Antigo', 'Novo', 'Usuário', 'Data' ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('produto-historico-preco/datatable'), 'order' => $filtro['order'], 'filtros' => ['codproduto'=>'codproduto', 'produto', 'referencia', 'codmarca', 'codusuario', 'alteracao_de', 'alteracao_ate', ] ])
    <style type="text/css">
        .table-danger, .table-danger > th, .table-danger > td {
            background-color: #fff !important;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
