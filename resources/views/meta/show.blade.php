@extends('layouts.default')
@section('content')
<?php
    $filiais    = collect($dados['filiais']);
    $vendedores = collect($dados['vendedores']);
    $xeroxs     = collect($dados['xerox']);
    $metasfiliais = $model->MetaFilialS()->get();
    $if = 1;
    $iv = 1;

    $feriado = new MGLara\Repositories\FeriadoRepository();
    $dias_uteis = $feriado->diasUteis($model->periodoinicial, ($model->periodofinal <= Carbon\Carbon::today() ? $model->periodofinal : Carbon\Carbon::today()), true);
    $datas = [];
    
    foreach ($dias_uteis as $dia){
        $datas[] = $dia->toW3cString();        
    }
    
    $colunas = [];
    foreach($filiais as $filial) {
        $colunas[$filial['filial']] = [$filial['filial']];
        foreach($filial['valorvendaspordata'] as $vendas) {
            array_push($colunas[$filial['filial']], $vendas['valorvendas']);
        }
    }
?>
<ul class="nav nav-pills">
    @foreach($anteriores as $meta)
    <li class="nav-item"><a class="nav-link" href="{{ url("meta/$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
    <li class="nav-item"><a class="nav-link active" href="#">{{ formataData($model->periodofinal, 'EC') }}</a></li>
    @foreach($proximos as $meta)
    <li class="nav-item"><a class="nav-link" href="{{ url("meta/$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
</ul>        
<div>
    <br>
    @if(count($metasfiliais) > 0)
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#geral" aria-controls="geral" role="tab" data-target="#geral" data-toggle="tab">Geral</a>
        </li>
        @foreach($metasfiliais as $metafilial)
        <li class="nav-item">
            <a class="nav-link" href="{{ url("meta/{$model->codmeta}?codfilial=$metafilial->codfilial") }}" aria-controls="{{ $metafilial->codfilial }}" data-target="#{{ $metafilial->codfilial }}" role="tab" data-toggle="tab" class="tab-filial">{{ $metafilial->Filial->filial }}</a>
        </li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="geral">
            <div class='card'>
                <h4 class="card-header">Total de vendas</h4>
                <div class='card-block'>
                    <div class="panel panel-default">            
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Filial</th>
                                    <th>Sub-Gerente</th>
                                    <th class="text-right">Meta</th>
                                    <th class="text-right">Meta Vendedor</th>
                                    <th class="text-right">Vendas</th>
                                    <th class="text-right">Falta</th>
                                    <th class="text-right">Comissão</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filiais as $filial)
                                <tr>
                                    <td scope="row">{{ $filial['filial'] }}</td>
                                    <td>
                                        <a href="{{ url('pessoa/'.$filial['codpessoa']) }}">{{ $filial['pessoa'] }}</a>
                                        <span class="label label-success pull-right">{{ $if++ }}º</span>
                                    </td>
                                    <td class="text-right"><span class="text-muted">{{ formataNumero($filial['valormetafilial']) }}</span></td>
                                    <td class="text-right"><span class="text-muted">{{ formataNumero($filial['valormetavendedor']) }}</span></td>
                                    <td class="text-right"><strong>{{ formataNumero($filial['valorvendas']) }}</strong></td>
                                    <td class="text-right">
                                        <span class="text-danger">{{ formataNumero($filial['falta']) }}</span>
                                        @if($filial['comissao'])
                                            <span class="label label-success">Atingida</span>
                                        @endif                                
                                    </td>
                                    <td class="text-right">{{ formataNumero($filial['comissao']) }}</td>
                                </tr>
                                @endforeach
                            </tbody> 
                        </table>
                    </div>
                    <div class='clearfix'></div>
                </div>
            </div>
            
            <div class='card'>
                <h4 class="card-header">Vendedores</h4>
                <div class='card-block'>
                    <div class="panel panel-default">            
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Filial</th>
                                    <th>Vendedor</th>
                                    <th class="text-right">Meta</th>
                                    <th class="text-right">Vendas</th>
                                    <th class="text-right">Falta</th>
                                    <th class="text-right">Comissão</th>
                                    <th class="text-right">Prêmio</th>
                                    <th class="text-right">Primeiro</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendedores->sortByDesc('valorvendas') as $vendedor)
                                <tr>
                                    <td scope="row">{{ $vendedor['filial'] }}</td>
                                    <td>
                                        <a href="{{ url('pessoa/'.$vendedor['codpessoa']) }}">{{ $vendedor['pessoa'] }}</a>
                                        <span class="label label-success pull-right">{{ $iv++ }}º</span>
                                    </td>
                                    <td class="text-right"><span class="text-muted">{{ formataNumero($vendedor['valormetavendedor']) }}</span></td>
                                    <td class="text-right"><strong>{{ formataNumero($vendedor['valorvendas']) }}</strong></td>
                                    <td class="text-right">
                                        <span class="text-danger">{{ formataNumero($vendedor['falta']) }}</span>
                                        @if($vendedor['metaatingida'])
                                            <span class="label label-success">Atingida</span>
                                        @endif                                
                                    </td>
                                    <td class="text-right">{{ formataNumero($vendedor['valorcomissaovendedor']) }}</td>
                                    <td class="text-right">{{ formataNumero($vendedor['valorcomissaometavendedor']) }}</td>
                                    <td class="text-right">{{ formataNumero($vendedor['primeirovendedor']) }}</td>
                                    <td class="text-right"><strong>{{ formataNumero($vendedor['valortotalcomissao']) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody> 
                        </table>
                    </div>
                </div>
            </div>            
            
            <div class='card'>
                <h4 class="card-header">Xerox</h4>
                <div class='card-block'>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Filial</th>
                                <th>Vendedor</th>
                                <th class="text-right">Vendas</th>
                                <th class="text-right">Comissão</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($xeroxs as $xerox)
                            <tr>
                                <td>{{ $xerox['filial'] }}</td>
                                <td><a href="{{ url('pessoa/'.$xerox['codpessoa']) }}">{{ $xerox['pessoa'] }}</a></td>
                                <td class="text-right"><strong>{{ formataNumero($xerox['valorvendas']) }}</strong></td>
                                <td class="text-right"><strong>{{ formataNumero($xerox['comissao']) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody> 
                    </table>     
                </div>
            </div>            
            
            <div class='card'>
                <h4 class="card-header">Gráfico divisão</h4>
                <div class='card-block'>
                    <div id="pieChart" style="height: 400px; width: 100%"></div>     
                </div>
            </div>            

            <div class='card'>
                <h4 class="card-header">Gráfico vendas por dia</h4>
                <div class='card-block'>
                    <div id="lineChart"></div>
                </div>
            </div>            

            <script type="text/javascript">
                var chart = c3.generate({
                    bindto: "#pieChart",
                    data: {
                        columns: [
                        @foreach($filiais as $filial)
                        ["{{ $filial['filial'] }}", {{ $filial['valorvendas'] }}],
                        @endforeach
                        ],
                        type : 'pie',
                    }
                });                   
                var lineChart = c3.generate({
                    bindto: "#lineChart",
                    data: {
                        x : 'date',
                        columns: [
                            ['date' 
                                @foreach($datas as $data)
                                <?php $data = Carbon\Carbon::parse($data);?>
                                ,"{{ $data->toDateString() }}"
                                @endforeach
                            ]
                            @foreach(array_values($colunas) as $coluna)
                            <?php $v = $coluna[0]; array_shift($coluna)?>
                            ,["{{$v}}", {{ implode(',', $coluna) }}]
                            @endforeach
                        ]
                    },
                    axis : {
                        x : {
                            type : 'timeseries',
                            tick : {
                                format: '%d',
                                culling: false
                            }
                        }
                    }
                });
                var pie = [];
            </script>            
        </div>
        @foreach($metasfiliais as $filial)
        <div role="tabpanel" class="tab-pane" id="{{ $filial['codfilial'] }}">
            @include('meta.filial', [
                'vendedores'    => $vendedores->where('codfilial', $filial['codfilial']),
                'filiais'       => $filiais->where('codfilial', $filial['codfilial']),
                'xeroxs'        => $xeroxs->where('codfilial', $filial['codfilial']),
                'i'            => 1
            ])
        </div>
        @endforeach
    </div>
@else
<h3>Nenhuma filial cadastrada para esse meta!</h3>
@endif
</div>
@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("meta/create") }}"><i class="fa fa-plus"></i></a>
    <a class="btn btn-secondary btn-sm" href="{{ url("meta/$model->codmeta/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("meta/$model->codmeta/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ formataData($model->periodofinal, 'EC') }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("meta/$model->codmeta/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ formataData($model->periodofinal, 'EC') }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("meta/$model->codmeta") }}" data-delete data-question="Tem certeza que deseja excluir '{{ formataData($model->periodofinal, 'EC') }}'?" data-after="location.replace('{{ url('meta') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<style type="text/css">
    .nav-tabs .nav-link.active, 
    .nav-tabs .nav-link.active:focus, 
    .nav-tabs .nav-link.active:hover, 
    .nav-tabs .nav-item.open .nav-link, 
    .nav-tabs .nav-item.open .nav-link:focus, 
    .nav-tabs .nav-item.open .nav-link:hover {
        background-color: #ffffff !important;
    }    
</style>
<link href="{{ URL::asset('public/assets/plugins/c3/c3.min.css') }}" rel="stylesheet" type="text/css">
<script src="{{ URL::asset('public/assets/plugins/d3/d3.min.js') }}" charset="utf-8"></script>
<script src="{{ URL::asset('public/assets/plugins/c3/c3.min.js') }}" charset="utf-8"></script>

@endsection
@stop