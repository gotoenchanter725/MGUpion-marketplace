@extends('layouts.default')
@section('content')
<?php

    function badge($quantidade) {
        if ($quantidade == 0) {
            $class = 'badge-default';
        } elseif ($quantidade < 0) {
            $class = 'badge-danger';
        } else {
            $class = 'badge-primary';
        }
        
        ?><span class="badge badge-pill pull-right {{ $class }}">{{ formataNumero($quantidade, 0) }}</span><?php
    }
    
    
    $class_saldoquantidade = 'badge-default';
    if (!empty($elpv) && !empty($es)) {
        if (!empty($elpv->estoqueminimo) && ($es->saldoquantidade <= $elpv->estoqueminimo)) {
            $class_saldoquantidade = 'badge-danger';
        } elseif (!empty($elpv->estoquemaximo) && ($es->saldoquantidade >= $elpv->estoquemaximo)) {
            $class_saldoquantidade = 'badge-warning';
        } elseif (!empty($elpv->estoqueminimo) && !empty($elpv->estoquemaximo) && ($es->saldoquantidade < $elpv->estoquemaximo) && ($es->saldoquantidade > $elpv->estoqueminimo)) {
            $class_saldoquantidade = 'badge-success';
        }
        
    }
    
    use Carbon\Carbon;
    Carbon::setLocale('pt_BR');
    $str_fiscal = ($fiscal)?'fiscal':'fisico';
    $saldodias = null;
    if (!empty($elpv->vendadiaquantidadeprevisao)) {
        $saldodias = $es->saldoquantidade / $elpv->vendadiaquantidadeprevisao;
    }
    

    $class_vencimento = 'text-muted';
    if ((!empty($elpv)) && (!empty($elpv->vencimento))) {
        if ($elpv->vencimento->isPast()) {
            $class_vencimento = 'text-danger';
        } else {
            $dias = $elpv->vencimento->diffInDays();
            if ($dias > $saldodias) {
              $class_vencimento = 'text-success';
            } elseif ($dias < 30) {
              $class_vencimento = 'text-danger';
            } else {
              $class_vencimento = 'text-warning';
            }
        }
    }
    
?>
        
<div class='row'>
  <div class='col-md-2'>
        <div class='card'>
          <div class='card-block'>
            <ul class="nav nav-pills">
            @foreach ($slds as $item)
              <div class='row'>
                <li class="nav-item col-md-12">
                  <a class="nav-link {{ ($str_fiscal == $item['chave'])?'active':'' }}" href='{{ url("kardex/{$el->codestoquelocal}/{$pv->codprodutovariacao}/{$item['chave']}/$ano/$mes") }}'>
                    {{ $item['descricao'] }}
                    {{ badge($item['saldoquantidade']) }}
                  </a>
                </li>            
              </div>
            @endforeach
            </ul>
          </div>
        </div>
      
        <div class='card'>
          <div class='card-block'>
            <ul class="nav nav-pills">
              @foreach ($els as $item)
                <div class='row'>
                  <li class="nav-item col-md-12">
                    <a class="nav-link {{ ($item['codestoquelocal']==$el->codestoquelocal)?'active':''  }}" href='{{ url("kardex/{$item['codestoquelocal']}/{$pv->codprodutovariacao}/$str_fiscal/$ano/$mes") }}'>
                      {{ $item['estoquelocal'] }}
                      {{ badge($item['saldoquantidade']) }}
                    </a>
                  </li>            
                </div>
              @endforeach
            </ul>
          </div>
        </div>
        <div class='card'>
          <div class='card-block'>
            <ul class="nav nav-pills">
              <div class='row'>
                <li class="nav-item col-md-12">
                  <a class="nav-link active" href='{{ url("kardex/{$el->codestoquelocal}/{$pv->codprodutovariacao}/$str_fiscal/$ano/$mes") }}'>
                    {{ $pv->variacao or '{Sem Variação}' }}
                    @if (isset($pvs[$pv->codprodutovariacao]))
                      {{ badge($pvs[$pv->codprodutovariacao]['saldoquantidade']) }}
                    @endif
                  </a>
                </li>            
              </div>
              @if (count($pvs) > 1)
                <hr>
                @foreach ($pvs as $item)
                  @if ($item['codprodutovariacao'] != $pv->codprodutovariacao)
                  <div class='row'>
                    <li class="nav-item col-md-12">
                      <a class="nav-link" href='{{ url("kardex/{$el->codestoquelocal}/{$item['codprodutovariacao']}/$str_fiscal/$ano/$mes") }}'>
                        {{ $item['variacao'] }}
                        {{ badge($item['saldoquantidade']) }}
                      </a>
                    </li>            
                  </div>
                  @endif
                @endforeach
              @endif
            </ul>
          </div>
        </div>
      </div>        
    
    <div class='col-md-10'>
      

    @if (!empty($elpv))
      <div class='row'>
        @if (!empty($es))
        
            <!-- Saldo em Dias -->
            <div class='col-md-3'>
                <div class="card">
                  <div class='card-block'>
                    <h5 class="text-muted m-b-20">
                      Saldo
                      <small class='pull-right'>
                        @if (!empty($elpv->corredor))
                          <i class="fa fa-cubes" aria-hidden="true"></i>
                          {{ formataLocalEstoque($elpv->corredor, $elpv->prateleira, $elpv->coluna, $elpv->bloco) }}
                        @endif
                      </small>
                    </h5>
                    <i class="fa fa-cube fa-3x pull-right text-muted"></i>
                    <h2 class="m-b-20">
                      @if (!empty($saldodias))
                        {{ formataNumero($saldodias, 1) }} <small class='text-muted'>Dias</small>
                      @else
                        &infin;
                      @endif
                    </h2>
                    <div class='row'>
                      <div class='col-md-5'>
                        <span class="badge {{ $class_saldoquantidade }}"> {{ formataNumero($es->saldoquantidade, 0) }} </span> <span class="text-muted">{{ $pv->Produto->UnidadeMedida->sigla }}</span><br>
                        <span class="badge {{ $class_saldoquantidade }}"> {{ formataNumero($es->saldovalor, 2) }} </span> <span class="text-muted">R$</span><br>
                      </div>
                      <div class='col-md-7 text-right'>
                        @if (!empty($elpv->estoqueminimo))
                          <span class='text-danger'>
                            <i class='fa fa-arrow-down'></i>{{ formataNumero($elpv->estoqueminimo, 0) }}
                          </span>
                        @endif
                        @if (!empty($elpv->estoquemaximo))
                          <span class='text-warning'>
                            <i class='fa fa-arrow-up'></i>{{ formataNumero($elpv->estoquemaximo, 0) }}
                          </span>
                        @endif
                        <br>
                        <span class="text-muted"> R$ {{ formataNumero($es->customedio, 6) }} / {{ $pv->Produto->UnidadeMedida->sigla }}<br>
                      </div>
                      
                    </div>
                  </div>        
                </div>        
            </div>
            
        @endif
        
        <!-- Venda em Quantidade -->
        <div class='col-md-3'>
            <div class="card">
              <div class='card-block'>
                <h5 class="text-muted m-b-20">Giro Anual</h5>
                <i class="fa fa-refresh fa-3x pull-right text-muted"></i>                
                @if (!empty($elpv->vendaanoquantidade))
                  <h2 class="m-b-20">{{ formataNumero($elpv->vendaanoquantidade, 0) }} <small class='text-muted'>{{ $pv->Produto->UnidadeMedida->sigla }}</small></h2>
                @else
                  <p class='text-danger'>
                    Não existem registros de venda.
                  </p>
                @endif
                @if ($elpv->vendabimestrequantidade > 0)
                  <span class="badge badge-success"> {{ formataNumero($elpv->vendabimestrequantidade, 0) }} </span> <span class="text-muted">Bimestre</span><br>
                @endif
                @if ($elpv->vendasemestrequantidade > 0)
                  <span class="badge badge-success"> {{ formataNumero($elpv->vendasemestrequantidade, 0) }} </span> <span class="text-muted">Semestre</span><br>
                @endif
              </div>        
            </div>        
        </div>      
        
        <!-- Venda em Valor -->
        <div class='col-md-3'>
            <div class="card">
              <div class='card-block'>
                <h5 class="text-muted m-b-20">Faturamento Anual</h5>
                <i class="fa fa-usd fa-3x pull-right text-muted"></i>                
                @if (!empty($elpv->vendaanoquantidade))
                  <h2 class="m-b-20"><small class='text-muted'>R$</small> {{ formataNumero($elpv->vendaanovalor, 2) }}</h2>
                @else
                  <p class='text-danger'>
                    Não existem registros de venda.
                  </p>
                @endif
                
                @if ($elpv->vendabimestrevalor > 0)
                  <span class="badge badge-success"> {{ formataNumero($elpv->vendabimestrevalor, 2) }} </span> <span class="text-muted">Bimestre</span><br>
                @endif
                @if ($elpv->vendasemestrevalor > 0)
                  <span class="badge badge-success"> {{ formataNumero($elpv->vendasemestrevalor, 2) }} </span> <span class="text-muted">Semestre</span><br>
                @endif
              </div>        
            </div>        
        </div>
        
        <!-- Movimentação -->
        @if (!empty($es))
          <div class='col-md-3'>
              <div class="card">
                <div class='card-block'>
                  <h5 class="text-muted">Movimentação</h5>
                  <i class="fa fa-calendar fa-3x pull-right text-muted"></i>

                  <small class='text-muted'>Entrou</small>
                  <h6 class="">
                    @if (!empty($es->dataentrada))
                      {{ $es->dataentrada->diffForHumans() }}
                    @else
                      Sem registro!
                    @endif
                  </h6>
                  
                  <small class='text-muted'>
                    Conferido 
                  </small>
                  <h6 class="">
                    @if (!empty($es->ultimaconferencia))
                      <a href="#" data-toggle="collapse" data-target="#collapseTimelineConferencia" aria-expanded="false" aria-controls="collapseTimelineConferencia">
                        {{ $es->ultimaconferencia->diffForHumans() }}
                      </a>
                    @else
                      Nunca
                    @endif
                    <a class='btn btn-secondary btn-sm' href="{{ url("estoque-saldo-conferencia/create?codestoquesaldo={$es->codestoquesaldo}") }}">
                      <i class="fa fa-plus" ></i>
                    </a> 
                  </h6>

                  @if (!empty($elpv->vencimento))
                    <small class='text-muted'>Vencimento</small>
                    <h6 class="{{ $class_vencimento }}">
                      {{ $elpv->vencimento->diffForHumans() }}
                    </h6>
                  @endif

                </div>        
              </div>        
          </div>
        @endif
        
    </div>      

    @endif
    
    <div class="collapse" id="collapseTimelineConferencia">
        @if (!empty($es))
          @include('estoque-mes.show-timeline-conferencia', ['es' => $es])
        @endif
    </div>

    <div class="card">
      <div class="card-block">
        <ul class="nav nav-pills row">
            @forelse ($ems as $eml)
            <div class="col-md-2">
              <li class="nav-item" style="width: 100%">
                <a class="nav-link {{ (!empty($em) && ($eml->codestoquemes == $em->codestoquemes))?'active':'' }}" href='{{ url("kardex/{$el->codestoquelocal}/{$pv->codprodutovariacao}/$str_fiscal/{$eml->mes->year}/{$eml->mes->month}") }}'>
                      {{ ucfirst($eml->mes->formatLocalized('%B/%y')) }}
                      <br>
                      <div class="">
                      <small class="text-muted">{{ formataNumero($eml->customedio, 6) }}</small>
                      {{ badge($eml->saldoquantidade) }}
                      </div>
                </a>
              </li>
            </div>
            @empty
              <li class="nav-item">
                <p>Não há movimentação em mês algum!</p>
              </li>
            @endforelse
        </ul>
      </div>
      <div class='card-block'>
        @if (!empty($movs))
          @include('estoque-mes.show-kardex', $movs)
        @endif
      </div>
    </div>
  </div>
</div>
        


@section('buttons')

    <?php

    $codestoquelocal = null;
    $codprodutovariacao = null;

    if (isset($elpv)) {
        $codestoquelocal = $elpv->codestoquelocal;
        $codprodutovariacao = $elpv->codprodutovariacao;
    }

    ?>
    <a class='btn btn-secondary btn-sm' href='{{ url("estoque-movimento/create?codestoquelocal={$codestoquelocal}&codprodutovariacao={$codprodutovariacao}&fiscal=$str_fiscal&ano={$ano}&mes={$mes}") }}'>
      <i class='fa fa-plus'></i>
    </a>

@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$em])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$em])
    
@endsection
@section('inscript')

@include('layouts.includes.datatable.assets')

<?php 
/*
@include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('usuario/datatable'), 'order' => $filtro['order'], 'filtros' => ['codusuario', 'usuario', 'codfilial', 'codpessoa', 'inativo'] ])
*/
?>

<script type="text/javascript">
    $(document).ready(function () {
        
    });

</script>
@endsection
@stop