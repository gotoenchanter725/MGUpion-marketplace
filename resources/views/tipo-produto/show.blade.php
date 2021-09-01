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
                      <td>{{ formataCodigo($model->codtipoproduto) }}</td> 
                    </tr>
                    <tr> 
                      <th>Tipo de Produto</th> 
                      <td>{{ $model->tipoproduto }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("tipo-produto/$model->codtipoproduto/edit") }}"><i class="fa fa-pencil"></i></a>
    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("tipo-produto/$model->codtipoproduto/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->tipoproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("tipo-produto/$model->codtipoproduto/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->tipoproduto }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("tipo-produto/$model->codtipoproduto") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->tipoproduto }}'?" data-after="location.replace('{{ url('tipo-produto') }}');"><i class="fa fa-trash"></i></a>                
    
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