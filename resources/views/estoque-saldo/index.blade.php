@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro, ['route' => 'estoque-saldo.index', 'method' => 'GET', 'id' => 'form-search', 'autocomplete' => 'on'])!!}
            <div class="col-md-4">
                <div class="form-group">
                    <label for="codestoquelocal" class="control-label">Local</label>
                    {!! Form::select2EstoqueLocal('codestoquelocal', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="codmarca" class="control-label">Marca</label>
                    {!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="codproduto" class="control-label">Produto</label>
                    {!! Form::select2Produto('codproduto', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="corredor" class="control-label">Corredor</label>
                    <div>
                    {!! Form::number('corredor', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px']) !!}
                    {!! Form::number('prateleira', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                    {!! Form::number('coluna', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                    {!! Form::number('bloco', null, ['class' => 'form-control pull-left', 'min' => '0', 'step' => 1, 'style'=>'width:60px; margin-left:10px']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('agrupamento', 'Por') !!}
                    <div class="clear">{!! Form::select2('agrupamento', $arr_agrupamentos, $agrupamento_atual, ['class' => 'form-control', 'placeholder' => 'Agrupado por...']) !!}</div>
                </div>                    
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('codsecaoproduto', 'Seção') !!}
                    {!! Form::select2SecaoProduto('codsecaoproduto', null, ['class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('codfamiliaproduto', 'Família') !!}
                    {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto',  'ativo'=>'9']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('codgrupoproduto', 'Grupo') !!}
                    {!! Form::select2GrupoProduto('codgrupoproduto', null, ['class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto', 'ativo'=>'9']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('codsubgrupoproduto', 'SubGrupo') !!}
                    {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['class' => 'form-control','id'=>'codsubgrupoproduto', 'placeholder' => 'Sub Grupo', 'codgrupoproduto'=>'codgrupoproduto', 'ativo'=>'9']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('saldo', 'Saldo') !!}
                    {!! Form::select2('saldo', $arr_saldos, null, ['class' => 'form-control', 'placeholder' => 'Saldo...']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('minimo', 'Mínimo') !!}
                    {!! Form::select2('minimo', $arr_minimo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Mínimo...']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('maximo', 'Máximo') !!}
                    {!! Form::select2('maximo', $arr_maximo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Máximo...']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('valor', 'Valor') !!}
                    {!! Form::select2('valor', $arr_valor, $valor, ['class' => 'form-control', 'placeholder' => 'Valorização...']) !!}
                </div>
            </div>

            <div class="clearfix"></div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>

<div class='card' id="div-estoque">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <b>Item</b>
            </div>
            <div class="col-md-2 text-right">
                Min <span class='fa fa-arrow-down'></span> 
                Max <span class='fa fa-arrow-up'></span> 
            </div>
            <div class="col-md-3 text-center">
                <b>Físico</b>
            </div>
            <div class="col-md-3 text-center">
                <b>Fiscal</b>
            </div>
        </div>                
        
    </div>
    <div class='card-block'>
        @foreach($itens as $coditem => $item)
            <?php
            $filtro[$codigo] = ($coditem=='total')?null:$coditem;
            $filtro['agrupamento'] = ($coditem=='total')?$agrupamento_atual:$agrupamento_proximo;
            ?>
        <div class="card">
            <div class="card-header {{ ($coditem == 'total')?'card-footer':'card-body' }}" role="tab" id="headingOne">
              <div class="mb-0">
                <small class='col-md-1 text-muted'>
                    @if (!empty($item['coditem']))
                        <a href="{{ url("{$url_detalhes}{$coditem}") }}">
                            {{ formataCodigo($item['coditem'], ($codigo=='codproduto')?6:8) }}
                        </a>
                        @if ($agrupamento_atual != 'variacao')
                            <span class='pull-right'>
                                <a href="{{ urlArrGet($filtro, 'estoque-saldo') }}">
                                        <span class='fa fa-plus-square'></span>
                                </a>
                            </span>
                        @endif
                    @endif
                </small>      
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseItem{{ $coditem }}" aria-expanded="true" aria-controls="collapseOne">
                    <div class='col-md-3'>
                        <b>
                            {{ ($coditem == 'total')?'Total':$item['item'] }}
                        </b>
                    </div>
                    <div class='col-md-2 text-right'>
                        {!! formataEstoqueMinimoMaximo($item['estoquelocal']['total']['estoqueminimo'], $item['estoquelocal']['total']['estoquemaximo'], $item['estoquelocal']['total']['fisico']['saldoquantidade']) !!}
                    </div>
                    <div class='col-md-2 text-right {{ ($item['estoquelocal']['total']['fisico']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                        {{ formataNumero($item['estoquelocal']['total']['fisico']['saldoquantidade'], 0) }}
                    </div>
                    <div class='col-md-1 text-right text-muted'>
                        <small>
                            {{ formataNumero($item['estoquelocal']['total']['fisico']['saldovalor'], 2) }}
                        </small>
                    </div>
                    <div class='col-md-2 text-right {{ ($item['estoquelocal']['total']['fiscal']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                        {{ formataNumero($item['estoquelocal']['total']['fiscal']['saldoquantidade'], 0) }}
                    </div>
                    <div class='col-md-1 text-right text-muted'>
                        <small>
                            {{ formataNumero($item['estoquelocal']['total']['fiscal']['saldovalor'], 2) }}
                        </small>
                    </div>
                    <div class="clearfix"></div>
                </a>
              </div>
            </div>

            <div id="collapseItem{{ $coditem }}" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="card-block">
                    <ul class="list-group list-group-condensed list-group-striped list-group-hover list-group-condensed">
                        @foreach ($item['estoquelocal'] as $codestoquelocal => $local)
                            <?php
                            if ($codestoquelocal == 'total')
                                continue;
                            ?>
                            <li class="list-group-item">

                                <div class="row">
                                    <div class='col-md-2 text-muted'>
                                    </div>
                                    <div class='col-md-2 text-muted'>
                                        <small>
                                            <a href="{{ urlArrGet($filtro + ['codestoquelocal' => $codestoquelocal], 'estoque-saldo') }}" class="">
                                                <span class='fa fa-plus-square'></span>
                                            </a>
                                        </small>
                                        &nbsp;
                                        {{ $local['estoquelocal'] }}
                                    </div>
                                    <div class='col-md-2 text-right'>
                                        {!! formataEstoqueMinimoMaximo($local['estoqueminimo'], $local['estoquemaximo'], $local['fisico']['saldoquantidade']) !!}
                                    </div>
                                    <div class='col-md-2 text-right {{ ($local['fisico']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                                        @if (!empty($local['fisico']['codestoquesaldo']))
                                            <small>
                                                <a href="{{ url("estoque-saldo/{$local['fisico']['codestoquesaldo']}") }}" class="">
                                                    <span class='fa fa-plus-square'></span>                                                
                                                </a>
                                            </small>
                                            &nbsp;
                                        @endif
                                        {{ formataNumero($local['fisico']['saldoquantidade'], 0) }}
                                    </div>
                                    <div class='col-md-1 text-right text-muted'>
                                        <small>
                                            {{ formataNumero($local['fisico']['saldovalor'], 2) }}
                                        </small>
                                    </div>
                                    <div class='col-md-2 text-right {{ ($local['fiscal']['saldoquantidade'] < 0)?'text-danger':'' }}'>
                                        @if (!empty($local['fiscal']['codestoquesaldo']))
                                            <small>
                                                <a href="{{ url("estoque-saldo/{$local['fiscal']['codestoquesaldo']}") }}" class="">
                                                    <span class='fa fa-plus-square'></span>                                                
                                                </a>
                                            </small>
                                            &nbsp;
                                        @endif
                                        {{ formataNumero($local['fiscal']['saldoquantidade'], 0) }}
                                    </div>
                                    <div class='col-md-1 text-right text-muted'>
                                        <small>
                                            {{ formataNumero($local['fiscal']['saldovalor'], 2) }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')
<script type="text/javascript">
    
function atualizaFiltro()
{
    var frmValues = $('#form-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/estoque-saldo',
        data: frmValues,
        dataType: 'html'
    })
    .done(function (data) {
        $('#div-estoque').html(jQuery(data).find('#div-estoque').html());
    })
    .fail(function () {
        console.log('Erro no filtro');
    });
}
    
$(document).ready(function() {

    $("#form-search").on("change", function (e) {
        if($('#form-search')[0].checkValidity()){
            $("#form-search").submit();
        }
        return false;
        
    }).on('submit', function (e){
        e.preventDefault();
        atualizaFiltro();
    });

});
</script>
@endsection
@stop
