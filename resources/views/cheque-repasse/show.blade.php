@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-6'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codchequerepasse }}</td> 
                    </tr>
                    <tr> 
                      <th>Portador</th> 
                      <td>{{ $model->Portador->portador }}</td> 
                    </tr>
                    <tr> 
                      <th>Data</th> 
                      <td>{{ formataData($model->data) }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacoes</th> 
                      <td>{{ $model->observacoes }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='card'>
            <h4 class="card-header">Cheques</h4>
            <div class='card-block'>
                <ul class="list-group">
                    @foreach ($model->ChequeRepasseChequeS as $emit)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-5">
                                
                                <strong>Vencimento:</strong> {{ formataData($emit->Cheque->vencimento) }} <br>
                                <strong>Banco:</strong> {{ $emit->Cheque->Banco->banco }} <br>
                                <strong>Agencia:</strong> {{ $emit->Cheque->agencia }} <br>
                            </div>
                            <div class="col-md-5">
                                <strong>Conta:</strong> {{ $emit->Cheque->contacorrente }} <br>
                                <strong>Numero:</strong> {{ $emit->Cheque->numero }} <br>
                                <strong>Valor:</strong> {{ formataNumero($emit->Cheque->valor, 2) }}
                            </div>
                            <div class="col-md-2">
                                <a href="{{ url('cheque', $emit->codcheque) }}">#{{ $emit->codcheque }} </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                
                
                
                
                
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("cheque-repasse/$model->codchequerepasse/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("cheque-repasse/$model->codchequerepasse/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codchequerepasse }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("cheque-repasse/$model->codchequerepasse/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codchequerepasse }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("cheque-repasse/$model->codchequerepasse") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codchequerepasse }}'?" data-after="location.replace('{{ url('cheque-repasse') }}');"><i class="fa fa-trash"></i></a>                
    
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