@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-4'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codestoquemovimento }}</td> 
                    </tr>
                    <tr> 
                      <th>Estoque Movimento</th> 
                      <td>{{ $model->codestoquemovimento }}</td> 
                    </tr>
                    <tr> 
                      <th>Codestoquemovimentotipo</th> 
                      <td>{{ $model->codestoquemovimentotipo }}</td> 
                    </tr>
                    <tr> 
                      <th>Entradaquantidade</th> 
                      <td>{{ $model->entradaquantidade }}</td> 
                    </tr>
                    <tr> 
                      <th>Entradavalor</th> 
                      <td>{{ $model->entradavalor }}</td> 
                    </tr>
                    <tr> 
                      <th>Saidaquantidade</th> 
                      <td>{{ $model->saidaquantidade }}</td> 
                    </tr>
                    <tr> 
                      <th>Saidavalor</th> 
                      <td>{{ $model->saidavalor }}</td> 
                    </tr>
                    <tr> 
                      <th>Codnegocioprodutobarra</th> 
                      <td>{{ $model->codnegocioprodutobarra }}</td> 
                    </tr>
                    <tr> 
                      <th>Codnotafiscalprodutobarra</th> 
                      <td>{{ $model->codnotafiscalprodutobarra }}</td> 
                    </tr>
                    <tr> 
                      <th>Codestoquemes</th> 
                      <td>{{ $model->codestoquemes }}</td> 
                    </tr>
                    <tr> 
                      <th>Manual</th> 
                      <td>{{ $model->manual }}</td> 
                    </tr>
                    <tr> 
                      <th>Data</th> 
                      <td>{{ $model->data }}</td> 
                    </tr>
                    <tr> 
                      <th>Codestoquemovimentoorigem</th> 
                      <td>{{ $model->codestoquemovimentoorigem }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacoes</th> 
                      <td>{{ $model->observacoes }}</td> 
                    </tr>
                    <tr> 
                      <th>Codestoquesaldoconferencia</th> 
                      <td>{{ $model->codestoquesaldoconferencia }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-movimento/$model->codestoquemovimento/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-movimento/$model->codestoquemovimento/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codestoquemovimento }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-movimento/$model->codestoquemovimento/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codestoquemovimento }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-movimento/$model->codestoquemovimento") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codestoquemovimento }}'?" data-after="location.replace('{{ url('estoque-movimento') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')

@endsection
@stop