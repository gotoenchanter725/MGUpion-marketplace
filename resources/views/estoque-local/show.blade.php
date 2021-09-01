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
                      <td>{{ $model->codestoquelocal }}</td> 
                    </tr>
                    <tr> 
                      <th>Local de Estoque</th> 
                      <td>{{ $model->estoquelocal }}</td> 
                    </tr>
                    <tr> 
                      <th>Codfilial</th> 
                      <td>{{ $model->codfilial }}</td> 
                    </tr>
                    <tr> 
                      <th>Sigla</th> 
                      <td>{{ $model->sigla }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-local/$model->codestoquelocal/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-local/$model->codestoquelocal/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->estoquelocal }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-local/$model->codestoquelocal/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->estoquelocal }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-local/$model->codestoquelocal") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->estoquelocal }}'?" data-after="location.replace('{{ url('estoque-local') }}');"><i class="fa fa-trash"></i></a>                
    
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