@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codestoquemovimento" class="control-label">#</label>
                        {!! Form::number('codestoquemovimento', null, ['class'=> 'form-control', 'id'=>'codestoquemovimento', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="codestoquemovimento" class="control-label">Estoque Movimento</label>
                        {!! Form::text('codestoquemovimento', null, ['class'=> 'form-control', 'id'=>'codestoquemovimento']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codestoquemovimentotipo" class="control-label">Codestoquemovimentotipo</label>
                        {!! Form::text('codestoquemovimentotipo', null, ['class'=> 'form-control', 'id'=>'codestoquemovimentotipo']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="entradaquantidade" class="control-label">Entradaquantidade</label>
                        {!! Form::text('entradaquantidade', null, ['class'=> 'form-control', 'id'=>'entradaquantidade']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="entradavalor" class="control-label">Entradavalor</label>
                        {!! Form::text('entradavalor', null, ['class'=> 'form-control', 'id'=>'entradavalor']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="saidaquantidade" class="control-label">Saidaquantidade</label>
                        {!! Form::text('saidaquantidade', null, ['class'=> 'form-control', 'id'=>'saidaquantidade']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="saidavalor" class="control-label">Saidavalor</label>
                        {!! Form::text('saidavalor', null, ['class'=> 'form-control', 'id'=>'saidavalor']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codnegocioprodutobarra" class="control-label">Codnegocioprodutobarra</label>
                        {!! Form::text('codnegocioprodutobarra', null, ['class'=> 'form-control', 'id'=>'codnegocioprodutobarra']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codnotafiscalprodutobarra" class="control-label">Codnotafiscalprodutobarra</label>
                        {!! Form::text('codnotafiscalprodutobarra', null, ['class'=> 'form-control', 'id'=>'codnotafiscalprodutobarra']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codestoquemes" class="control-label">Codestoquemes</label>
                        {!! Form::text('codestoquemes', null, ['class'=> 'form-control', 'id'=>'codestoquemes']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="manual" class="control-label">Manual</label>
                        {!! Form::text('manual', null, ['class'=> 'form-control', 'id'=>'manual']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="data" class="control-label">Data</label>
                        {!! Form::text('data', null, ['class'=> 'form-control', 'id'=>'data']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codestoquemovimentoorigem" class="control-label">Codestoquemovimentoorigem</label>
                        {!! Form::text('codestoquemovimentoorigem', null, ['class'=> 'form-control', 'id'=>'codestoquemovimentoorigem']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="observacoes" class="control-label">Observacoes</label>
                        {!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codestoquesaldoconferencia" class="control-label">Codestoquesaldoconferencia</label>
                        {!! Form::text('codestoquesaldoconferencia', null, ['class'=> 'form-control', 'id'=>'codestoquesaldoconferencia']) !!}
                    </div>
                </div>
                <div class="col-md-1">
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Estoque Movimento', 'Codestoquemovimentotipo', 'Entradaquantidade', 'Entradavalor', 'Saidaquantidade', 'Saidavalor', 'Codnegocioprodutobarra', 'Codnotafiscalprodutobarra', 'Codestoquemes', 'Manual', 'Data', 'Codestoquemovimentoorigem', 'Observacoes', 'Codestoquesaldoconferencia', ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-movimento/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('estoque-movimento/datatable'), 'order' => $filtro['order'], 'filtros' => ['codestoquemovimento', 'codestoquemovimento', 'inativo', 'codestoquemovimentotipo', 'entradaquantidade', 'entradavalor', 'saidaquantidade', 'saidavalor', 'codnegocioprodutobarra', 'codnotafiscalprodutobarra', 'codestoquemes', 'manual', 'data', 'codestoquemovimentoorigem', 'observacoes', 'codestoquesaldoconferencia', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
