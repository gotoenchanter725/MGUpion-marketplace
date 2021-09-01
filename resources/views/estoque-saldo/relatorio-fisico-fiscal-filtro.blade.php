@extends('layouts.default')
@section('content')

<div class="collapse in" id="collapsePesquisa">
    <div class="card">
        <h4 class="card-header">Pesquisa</h4>
        <div class="card-block">
            <div class="card-text">
                {!! Form::model($filtro, ['url' => 'estoque-saldo/relatorio-fisico-fiscal', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'estoque-saldo-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
                <div class="row">
                    <div class='col-md-4'>
                        <div class="form-group">
                            {!! Form::label('codempresa', 'Empresa') !!}
                            {!! Form::select2Empresa('codempresa', null, ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>                    
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('codsecaoproduto', 'Seção') !!}
                            {!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('codncm', 'NCM') !!}
                            {!! Form::select2Ncm('codncm', null, ['class' => 'form-control','id'=>'codncm', 'placeholder' => 'NCM']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('mes', 'Mês / Ano') !!}
                            <div class="input-group">
                                {!! Form::number('mes', null, ['class'=> 'form-control text-center', 'placeholder' => 'Mês', 'step' => 1, 'min' => '1', 'max' => '12', 'required' => true]) !!}
                                <span class="input-group-addon">/</span>
                                {!! Form::number('ano', null, ['class'=> 'form-control text-center', 'placeholder' => 'Ano', 'step' => 1, 'min' => 2015, 'max' => 2020, 'required' => true]) !!}
                            </div>
                        </div>                        
                    </div>                    
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('codfamiliaproduto', 'Família') !!}
                            {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto',  'ativo'=>'9']) !!}
                        </div>                        
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('saldo_fiscal', 'Fiscal') !!}
                            {!! Form::select2('saldo_fiscal', ['' => '', -1=>'Negativo', 1=>'Positivo'], null, ['class' => 'form-control', 'placeholder' => 'Fiscal...']) !!}
                        </div>
                    </div>                  
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('codestoquelocal', 'Local') !!}
                            {!! Form::select2EstoqueLocal('codestoquelocal', null, ['class' => 'form-control']) !!}
                        </div>                        
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('codgrupoproduto', 'Grupo') !!}
                            {!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto', 'ativo'=>'9']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('saldo_fisico', 'Fisico') !!}
                            {!! Form::select2('saldo_fisico', ['' => '', -1=>'Negativo', 1=>'Positivo'], null, ['class' => 'form-control', 'placeholder' => 'Fisico...']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('produto', 'Produto') !!}
                            {!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição do Produto...']) !!}
                        </div>
                    </div>                     
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('codsubgrupoproduto', 'SubGrupo') !!}
                            {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'placeholder' => 'Sub Grupo', 'codgrupoproduto'=>'codgrupoproduto', 'ativo'=>'9']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('saldo_fisico_fiscal', 'Comparação') !!}
                            {!! Form::select2('saldo_fisico_fiscal', ['' => '', -1=>'Faltando Fiscal', 1=>'Sobrando Fiscal'], null, ['class' => 'form-control', 'placeholder' => 'Fisico x Fiscal...']) !!}
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('preco_de', 'Preço') !!}
                            <div>
                                {!! Form::number('preco_de', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_de', 'placeholder' => 'De', 'style'=>'width:47%; margin-right:6%', 'step'=>'0.01']) !!}
                                {!! Form::number('preco_ate', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_ate', 'placeholder' => 'Até', 'style'=>'width:47%;', 'step'=>'0.01']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('codmarca', 'Marca') !!}
                            {!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}
                        </div>                        
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div><button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Buscar</button></div>
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

$(document).ready(function() {

    $('#preco_ate').attr('min', $('#preco_de').val());
    $('#preco_de').attr('max', $('#preco_ate').val());
    
    $('#preco_de').on('change', function(e) {
        $('#preco_ate').attr('min', $('#preco_de').val());
    });

    $('#preco_ate').on('change', function(e) {
        $('#preco_de').attr('max', $('#preco_ate').val());
    });

});
</script>
@endsection
@stop
