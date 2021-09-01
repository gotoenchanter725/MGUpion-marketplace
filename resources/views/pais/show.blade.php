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
                      <td>{{ $model->codpais }}</td> 
                    </tr>
                    <tr> 
                      <th>País</th> 
                      <td>{{ $model->pais }}</td> 
                    </tr>
                    <tr> 
                      <th>sigla</th> 
                      <td>{{ $model->sigla }}</td> 
                    </tr>
                    <tr> 
                      <th>codigooficial</th> 
                      <td>{{ $model->codigooficial }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("pais/$model->codpais/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("pais/$model->codpais/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->pais }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("pais/$model->codpais/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->pais }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("pais/$model->codpais") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->pais }}'?" data-after="location.replace('{{ url('pais') }}');"><i class="fa fa-trash"></i></a>                
    
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