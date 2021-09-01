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
                      <td>{{ $model->codprodutovariacao }}</td> 
                    </tr>
                    <tr> 
                      <th>Produto Variacao</th> 
                      <td>{{ $model->variacao }}</td> 
                    </tr>
                    <tr> 
                      <th>Codproduto</th> 
                      <td>{{ $model->codproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Referencia</th> 
                      <td>{{ $model->referencia }}</td> 
                    </tr>
                    <tr> 
                      <th>Codmarca</th> 
                      <td>{{ $model->codmarca }}</td> 
                    </tr>
                    <tr> 
                      <th>Codopencart</th> 
                      <td>{{ $model->codopencart }}</td> 
                    </tr>
                    <tr> 
                      <th>Dataultimacompra</th> 
                      <td>{{ $model->dataultimacompra }}</td> 
                    </tr>
                    <tr> 
                      <th>Custoultimacompra</th> 
                      <td>{{ $model->custoultimacompra }}</td> 
                    </tr>
                    <tr> 
                      <th>Quantidadeultimacompra</th> 
                      <td>{{ $model->quantidadeultimacompra }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("produto-variacao/$model->codprodutovariacao/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("produto-variacao/$model->codprodutovariacao/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->variacao }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("produto-variacao/$model->codprodutovariacao/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->variacao }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("produto-variacao/$model->codprodutovariacao") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->variacao }}'?" data-after="location.replace('{{ url('produto-variacao') }}');"><i class="fa fa-trash"></i></a>                
    
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