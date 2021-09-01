@extends('layouts.default')
@section('content')

<div class="collapse in" id="collapsePesquisa">
    <div class="card">
        <h4 class="card-header">Pesquisa</h4>
        <div class="card-block">
            <div class="card-text">
                {!! Form::model($filtro, ['url' => 'estoque-saldo/relatorio-analise', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'estoque-saldo-search', 'role' => 'search', 'autocomplete' => 'on' ]) !!}
                <div class="row">
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codestoquelocal', 'Local') !!}
                            {!! Form::select2EstoqueLocal('codestoquelocal', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codmarca', 'Marca') !!}
                            {!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            {!! Form::label('codproduto', 'Produto') !!}
                            {!! Form::select2Produto('codproduto', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codsecaoproduto', 'Seção') !!}
                            {!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codfamiliaproduto', 'Família') !!}
                            {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto',  'ativo'=>'9']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codgrupoproduto', 'Grupo') !!}
                            {!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto', 'ativo'=>'9']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codsubgrupoproduto', 'SubGrupo') !!}
                            {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'placeholder' => 'Sub Grupo', 'codgrupoproduto'=>'codgrupoproduto', 'ativo'=>'9']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('saldo', 'Saldo') !!}
                            {!! Form::select2('saldo', $arr_saldos, null, ['class' => 'form-control', 'placeholder' => 'Saldo...']) !!}
                        </div>
                    </div>                    
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('minimo', 'Mínimo') !!}
                            {!! Form::select2('minimo', $arr_minimo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Mínimo...']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('maximo', 'Máximo') !!}
                            {!! Form::select2('maximo', $arr_maximo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Máximo...']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('ativo', 'Ativos') !!}
                            {!! Form::select2('ativo', $arr_ativo, null, ['class' => 'form-control', 'placeholder' => 'Ativos...']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-4'>
                        {!! Form::label('corredor', 'Corredor') !!}
                        <div>
                            {!! Form::number('corredor', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:22%']) !!}
                            {!! Form::number('prateleira', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:22%; margin-left:4%']) !!}
                            {!! Form::number('coluna', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:22%; margin-left:4%']) !!}
                            {!! Form::number('bloco', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:22%; margin-left:4%']) !!}
                        </div>
                    </div>
                    <div class='col-md-4'>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div><button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Buscar</button></div>
                        </div>                    
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')
<script type="text/javascript">
    $(document).ready(function () {
        // ...
    });
</script>
@endsection
@stop
