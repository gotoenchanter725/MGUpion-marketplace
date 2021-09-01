@extends('layouts.default')
@section('content')

<div class='row'>
    
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codcheque }}</td> 
                    </tr>
                    <tr> 
                      <th>Valor</th> 
                      <td>R$ {{ formataNumero($model->valor, 2) }}</td> 
                    </tr>
                    <tr> 
                      <th>Data de Emissão</th> 
                      <td>{{ formataData($model->emissao) }}</td> 
                    </tr>
                    <tr> 
                      <th>Data de Vencimento</th> 
                      <td>{{ formataData($model->vencimento) }}</td> 
                    </tr>
                    <tr> 
                      <th>CMC7</th> 
                      <td>{{ $model->cmc7 }}</td> 
                    </tr>
                    <tr> 
                      <th>Pessoa</th> 
                      <td>
                      <a href="{{ url('pessoa', $model->codpessoa) }}">
                      {{ $model->Pessoa->pessoa }}
                      </a>
                      </td>
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
        <div class='card'>
            <h4 class="card-header">Emitentes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    @foreach ($model->ChequeEmitenteS as $emit)
                    <tr>
                        <td width='140'>{{ formataCpfCnpj($emit->cnpj) }}</td>
                        <td>{{ $emit->emitente }}</td>
                    </tr>
                    @endforeach
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>Banco</th> 
                      <td>{{ $model->Banco->banco }}</td> 
                    </tr>
                    <tr> 
                      <th>Agencia</th> 
                      <td>{{ $model->agencia }}</td> 
                    </tr>
                    <tr> 
                      <th>Conta Corrente</th> 
                      <td>{{ $model->contacorrente }}</td> 
                    </tr>
                    <tr> 
                      <th>Número do cheque</th> 
                      <td>{{ $model->numero }}</td> 
                    </tr>
                    <tr> 
                      <th>Status</th> 
                      <td><span class="{{ $status['label'] }}">{{ $status['status'] }}</span></td> 
                    </tr>
                    <tr> 
                      <th>Observação</th> 
                      <td>
                        @if (!empty($model->observacao))
                            {{ $model->observacao }}
                        @else
                            Não há observações cadastradas.
                        @endif
                      </td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
        
        <div class='card'>
            <h4 class="card-header">Repasses</h4>
            <div class='card-block'>
                <ul class="list-group">
                    @foreach ($model->ChequeRepasseChequeS as $repasse)
                        <li class="list-group-item">
                            <a href="{{ url('cheque-repasse', $repasse->codchequerepasse) }}">#{{ $repasse->codchequerepasse }} </a><br>
                            <strong>Data:</strong> {{ formataData($repasse->ChequeRepasse->data) }}<br>
                            <strong>Portador:</strong> {{ $repasse->ChequeRepasse->Portador->portador }}
                        </li>
                    @endforeach
                </ul>
                <div class='clearfix'></div>
            </div>
        </div>
        
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->cmc7 }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->cmc7 }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("cheque/$model->codcheque") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->cmc7 }}'?" data-after="location.replace('{{ url('cheque') }}');"><i class="fa fa-trash"></i></a>                
    
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