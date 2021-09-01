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
                      <td>{{ $model->codprodutobarra }}</td> 
                    </tr>
                    <tr> 
                      <th>Produto Barra</th> 
                      <td>{{ $model->variacao }}</td> 
                    </tr>
                    <tr> 
                      <th>Codproduto</th> 
                      <td>{{ $model->codproduto }}</td> 
                    </tr>
                    <tr> 
                      <th>Barras</th> 
                      <td>{{ $model->barras }}</td> 
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
                      <th>Codprodutoembalagem</th> 
                      <td>{{ $model->codprodutoembalagem }}</td> 
                    </tr>
                    <tr> 
                      <th>Codprodutovariacao</th> 
                      <td>{{ $model->codprodutovariacao }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("produto-barra/$model->codprodutobarra/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("produto-barra/$model->codprodutobarra/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->variacao }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("produto-barra/$model->codprodutobarra/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->variacao }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("produto-barra/$model->codprodutobarra") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->variacao }}'?" data-after="location.replace('{{ url('produto-barra') }}');"><i class="fa fa-trash"></i></a>                
    
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