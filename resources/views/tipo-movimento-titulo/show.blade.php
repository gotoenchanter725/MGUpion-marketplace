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
                      <td>{{ $model->codtipomovimentotitulo }}</td> 
                    </tr>
                    <tr> 
                      <th>Tipo Movimento Titulo</th> 
                      <td>{{ $model->tipomovimentotitulo }}</td> 
                    </tr>
                    <tr> 
                      <th>Implantacao</th> 
                      <td>{{ $model->implantacao }}</td> 
                    </tr>
                    <tr> 
                      <th>Ajuste</th> 
                      <td>{{ $model->ajuste }}</td> 
                    </tr>
                    <tr> 
                      <th>Armotizacao</th> 
                      <td>{{ $model->armotizacao }}</td> 
                    </tr>
                    <tr> 
                      <th>Juros</th> 
                      <td>{{ $model->juros }}</td> 
                    </tr>
                    <tr> 
                      <th>Desconto</th> 
                      <td>{{ $model->desconto }}</td> 
                    </tr>
                    <tr> 
                      <th>Pagamento</th> 
                      <td>{{ $model->pagamento }}</td> 
                    </tr>
                    <tr> 
                      <th>Estorno</th> 
                      <td>{{ $model->estorno }}</td> 
                    </tr>
                    <tr> 
                      <th>Observacao</th> 
                      <td>{{ $model->observacao }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("tipo-movimento-titulo/$model->codtipomovimentotitulo/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("tipo-movimento-titulo/$model->codtipomovimentotitulo/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->tipomovimentotitulo }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("tipo-movimento-titulo/$model->codtipomovimentotitulo/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->tipomovimentotitulo }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("tipo-movimento-titulo/$model->codtipomovimentotitulo") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->tipomovimentotitulo }}'?" data-after="location.replace('{{ url('tipo-movimento-titulo') }}');"><i class="fa fa-trash"></i></a>                
    
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