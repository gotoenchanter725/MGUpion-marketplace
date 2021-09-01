@extends('layouts.default')
@section('content')
<?php
$favorecidos = $modelos->unique(function ($item) {
    return $item['codpessoafavorecido'].$item['fantasia'];
});
?>
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <h4 class="card-header">Modelos</h4>
            <div class="card-block">
                <div class="col-md-4">
                    <ul class="nav nav-tabs nav-pills tabs-left" role="tablist">
                        @foreach ($favorecidos as $i => $fav)
                        <li class="nav-item">
                            <a class="nav-link {{ $i==0?'active':'' }}" data-toggle="tab" href="#menu{{ $fav->codpessoafavorecido }}" role="tab">{{ $fav->fantasia }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="tab-content">
                        @foreach ($favorecidos as $i => $fav)
                        <div class="tab-pane {{ $i==0?'active':'' }}" id="menu{{ $fav->codpessoafavorecido }}" role="tabpanel">
                            <table class="table table-striped table-hover table-sm">
                                <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Modelo</th>
                                      <th>Total</th>
                                      <th>Ano/Turma</th>
                                      <th>Observações</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    @foreach ($modelos->where('codpessoafavorecido', $fav->codpessoafavorecido) as $row)
                                    <tr> 
                                        <td><a href='{{ url("vale-compra/create?codvalecompramodelo=$row->codvalecompramodelo") }}'>{{ formataCodigo($row->codvalecompramodelo) }}</a></td> 
                                        <td><a href='{{ url("vale-compra/create?codvalecompramodelo=$row->codvalecompramodelo") }}'>{{ $row->modelo }}</a></td> 
                                        <td>{{ formataNumero($row->total) }}</td> 
                                        <td>{{ $row->ano }} / {{ $row->turma }}</td> 
                                        <td>{!! nl2br($row->observacoes) !!}</td> 
                                    </tr>
                                    @endforeach
                                </tbody> 
                            </table>                            
                        </div>
                        @endforeach  
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@section('inscript')
<link href="{{ URL::asset('public/assets/plugins/bootstrap-vertical-tabs/tabs.css') }}" rel="stylesheet">
<style type="text/css">
    .tabs-left {
        border-right: 0;
    }
    .tabs-left>li>a {
        border-radius:4px !important;
    }    
</style>
<script type="text/javascript">
$(document).ready(function() {
});
</script>
@endsection

@stop