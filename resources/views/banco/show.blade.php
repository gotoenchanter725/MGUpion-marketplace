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
                      <td>{{ $model->codbanco }}</td> 
                    </tr>
                    <tr> 
                      <th>Banco</th> 
                      <td>{{ $model->banco }}</td> 
                    </tr>
                    <tr> 
                      <th>Sigla</th> 
                      <td>{{ $model->sigla }}</td> 
                    </tr>
                    <tr> 
                      <th>Numerobanco</th> 
                      <td>{{ $model->numerobanco }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("banco/$model->codbanco/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("banco/$model->codbanco/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->banco }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("banco/$model->codbanco/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->banco }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("banco/$model->codbanco") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->banco }}'?" data-after="location.replace('{{ url('banco') }}');"><i class="fa fa-trash"></i></a>                
    
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