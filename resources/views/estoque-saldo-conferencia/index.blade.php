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
                        <label for="codestoquesaldoconferencia" class="control-label">#</label>
                        {!! Form::number('codestoquesaldoconferencia', null, ['class'=> 'form-control', 'id'=>'codestoquesaldoconferencia', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="codproduto" class="control-label">Produto</label>
                        {!! Form::select2Produto('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codestoquelocal" class="control-label">Local</label>
                        {!! Form::select2EstoqueLocal('codestoquelocal', null, ['class'=> 'form-control', 'id'=>'codestoquelocal']) !!}
                    </div>
                    <div class="form-group">
                        <label for="codusuariocriacao" class="control-label">Usuário</label>
                        {!! Form::select2Usuario('codusuariocriacao', null, ['class'=> 'form-control', 'id'=>'codusuariocriacao']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="data_de" class="control-label">De</label>
                        {!! Form::datetimeLocal('data_de', null, ['class'=> 'form-control', 'id'=>'data_de']) !!}
                    </div>
                    <div class="form-group">
                        <label for="data_ate" class="control-label">Até</label>
                        {!! Form::datetimeLocal('data_ate', null, ['class'=> 'form-control', 'id'=>'data_ate']) !!}
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
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Produto', 'Variação', 'Local', 'Quantidade', 'Custo', 'Data', 'Usuario', ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-saldo-conferencia/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('estoque-saldo-conferencia/datatable'), 'order' => $filtro['order'],'filtros' => ['codestoquesaldoconferencia', 'codproduto', 'codestoquelocal', 'inativo', 'codusuariocriacao', 'data_de', 'data_ate'], 'estilos'=>[ ['className' =>'text-right', 'targets'=>[6, 7]], ] ])

<script type="text/javascript">
    $(document).ready(function () {
        // ...
    });
</script>

@endsection
@stop
