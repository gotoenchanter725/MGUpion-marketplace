<?php

function decideIconeUltimaConferencia($data)
{
    if ($data == null){
        return 'fa-times-circle text-muted';
    }
    
    $dias = $data->diffInDays();
    
    if ($dias > 30){
        return 'fa-question-circle text-danger';
    }
  
    if ($dias > 15){
        return 'fa-question-circle text-warning';
    }
    
    return 'fa-check-circle-o text-success';
}

function divSaldo($arr) {
    ?>
    @if (!empty($arr['codestoquesaldo']))
        <a href="{{ url("estoque-saldo/{$arr['codestoquesaldo']}") }}">
    @endif
    {{ formataNumero($arr['saldoquantidade'], 0) }}
    <span class='fa {{ decideIconeUltimaConferencia($arr['ultimaconferencia']) }}'></span>
    @if (!empty($arr['codestoquesaldo']))
        </a>
    @endif
    <?php
}

function divDescricao($arr) {
    ?>
    <div class="col-xs-4">
        @if (is_array($arr['variacao'] ))
            <b>
            @if (!empty($arr['estoquelocal'] ))
                {{ $arr['estoquelocal'] }}
            @else
                Total
            @endif
            </b>
        @elseif (!empty($arr['variacao'] ))
            {{ $arr['variacao'] }}
        @else
            <i class='text-muted'>{ Sem Variação }</i>
        @endif
    </div>
    <div class="col-xs-4 text-muted">
        @if (isset($arr['corredor']))
            {{ formataLocalEstoque($arr['corredor'], (isset($arr['prateleira'])) ? $arr['prateleira'] : '', (isset($arr['coluna'])) ? $arr['coluna'] : '', (isset($arr['bloco'])) ? $arr['bloco'] : '') }}
        @endif
        <div class='pull-right'>
            @if (!empty($arr['estoqueminimo']))
                @if ($arr['estoqueminimo'] > $arr['fisico']['saldoquantidade'])
                    <b class='text-danger'>
                @endif
                {{ formataNumero($arr['estoqueminimo'], 0) }} <span class='fa fa-arrow-down'></span>
                @if ($arr['estoqueminimo'] > $arr['fisico']['saldoquantidade'])
                    </b>
                @endif
            @endif
            @if (!empty($arr['estoquemaximo']))
                @if ($arr['estoquemaximo'] < $arr['fisico']['saldoquantidade'])
                    <b class='text-danger'>
                @endif
                {{ formataNumero($arr['estoquemaximo'], 0) }} <span class='fa fa-arrow-up'></span>
                @if ($arr['estoquemaximo'] < $arr['fisico']['saldoquantidade'])
                    </b>
                @endif
            @endif
        </div>            
    </div>
    <?php
}

?>

<div id='div-estoque'>
    <div class="card">
        <div class="card-header" role="tab">
            <span class="mb-0">
                <div class="row">
                    <div class="col-xs-4">
                        <b>Local</b>
                    </div>
                    <div class="col-xs-4">
                        <b>Corredor</b>
                        <b class='pull-right'>
                            Min <span class='fa fa-arrow-down'></span> 
                            Max <span class='fa fa-arrow-up'></span> 
                        </b>
                    </div>
                    <div class="col-xs-2 text-right">
                        <b>Físico</b>
                    </div>
                    <div class="col-xs-2 text-right">
                        <b>Fiscal</b>
                    </div>
                </div>                        
            </span>
        </div>
    </div>        

    <div id="accordion" role="tablist" aria-multiselectable="true">
    @foreach($estoque['local'] as $codestoquelocal => $arrLocal)
        <div class="card">
            <div class="card-header" role="tab" id="heading{{ $codestoquelocal }}">
                <span class="mb-0">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseEstoqueLocal{{ $codestoquelocal }}" aria-expanded="true" aria-controls="collapseEstoqueLocal{{ $codestoquelocal }}">
                        <div class="row">
                            {{ divDescricao($arrLocal) }}
                            <div class="col-xs-2 text-right">
                                {{ divSaldo($arrLocal['fisico']) }}
                            </div>
                            <div class="col-xs-2 text-right">
                                {{ divSaldo($arrLocal['fiscal']) }}
                            </div>
                        </div>
                    </a>
                </span>
            </div>

            <div id="collapseEstoqueLocal{{ $codestoquelocal }}" class="collapse show" role="tabpanel" aria-labelledby="heading{{ $codestoquelocal }}">
                <ul class="list-group list-group-flush">
                    @foreach ($arrLocal['variacao'] as $codprodutovariacao => $arrVar)
                    <li class="list-group-item">
                        <div class="row">
                            {{ divDescricao($arrVar) }}
                            <div class="col-xs-2 text-right">
                                {{ divSaldo($arrVar['fisico']) }}
                            </div>
                            <div class="col-xs-2 text-right">
                                {{ divSaldo($arrVar['fiscal']) }}
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>                            
            </div>
        </div>
    @endforeach
    </div>
</div>