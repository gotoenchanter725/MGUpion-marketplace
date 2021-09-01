@@extends('layouts.default')
@@section('content')

<div class='row'>
    <div class='col-md-4'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ '{{' }} $model->{{ $instancia_model->getKeyName() }} }}</td> 
                    </tr>
                    <tr> 
                      <th>{{ $titulo }}</th> 
                      <td>{{ '{{' }} $model->{{ $coluna_titulo }} }}</td> 
                    </tr>
@foreach ($cols_listagem as $col)
                    <tr> 
                      <th>{{ ucfirst($col->column_name) }}</th> 
                      <td>{{ '{{' }} $model->{{ $col->column_name }} }}</td> 
                    </tr>
@endforeach
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ '{{' }} url("{{ $url }}/$model->{{ $instancia_model->getKeyName() }}/edit") }}"><i class="fa fa-pencil"></i></a>
    @@if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ '{{' }} url("{{ $url }}/$model->{{ $instancia_model->getKeyName() }}/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ '{{' }} $model->{{ $coluna_titulo }} }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @@else
        <a class="btn btn-secondary btn-sm" href="{{ '{{' }} url("{{ $url }}/$model->{{ $instancia_model->getKeyName() }}/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ '{{' }} $model->{{ $coluna_titulo }} }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @@endif
    <a class="btn btn-secondary btn-sm" href="{{ '{{' }} url("{{ $url }}/$model->{{ $instancia_model->getKeyName() }}") }}" data-delete data-question="Tem certeza que deseja excluir '{{ '{{' }} $model->{{ $coluna_titulo }} }}'?" data-after="location.replace('{{ '{{' }} url('{{ $url }}') }}');"><i class="fa fa-trash"></i></a>                
    
@@endsection
@@section('inactive')

    @@include('layouts.includes.inactive', [$model])
    
@@endsection
@@section('creation')

    @@include('layouts.includes.creation', [$model])
    
@@endsection
@@section('inscript')

@@endsection
@@stop