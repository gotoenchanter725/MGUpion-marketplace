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
              <td>{{ formataCodigo($model->codmarca) }}</td> 
            </tr>
            <tr> 
              <th>Marca</th> 
              <td>{{ $model->marca }}</td> 
            </tr>
            <tr> 
              <th>Disponível no site</th> 
              <td>{{ ($model->site == 1  ?'Sim':'Não') }}</td> 
            </tr>
            <tr> 
              <th>Descrição</th> 
              <td>{{ $model->descricaosite }}</td> 
            </tr>
          </tbody> 
        </table>
        <div class='clearfix'></div>
      </div>
    </div>
  </div>
  <div class='col-md-4'>
    <div class='card'>
      <h4 class="card-header">
        Imagem
        @if ($model->codimagem)
        <div class="btn-group">
          <a class="btn btn-secondary btn-sm waves-effect" data-toggle="tooltip" title="Editar" href="{{ url("/imagem/{$model->Imagem->codimagem}/edit") }}"><i class="fa fa-pencil"></i></a>
          <a class="btn btn-secondary btn-sm waves-effect" data-toggle="tooltip" title="Excluir Imagem" href="{{ url("imagem/{$model->Imagem->codimagem}/inactivate") }}" data-activate data-question="Tem certeza que deseja excluir esta imagem?" data-after="location.reload()"><i class="fa fa-trash"></i></a>
        </div>        
        @else
        <a class="btn btn-sm btn-secondary waves-effect" data-toggle="tooltip" href="{{ url("/imagem/create?codmarca=$model->codmarca") }}" title="Cadastrar imagem">
          <i class="fa fa-plus"></i> 
        </a>
        @endif
      </h4>
      <div class='card-block'>
        @if($model->codimagem)
        <a href="{{ url("imagem/{$model->Imagem->codimagem}") }}">
          <img class="img-fluid" src='{{ $model->Imagem->url }}'>
        </a>
        @else
          <img class="img-fluid" src='{{ asset('public/imagens/semimagem.jpg') }}'>
        @endif
        <div class='clearfix'></div>        
      </div>
    </div>
  </div>
</div>
      
@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca/edit") }}"><i class="fa fa-pencil"></i></a>
    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->marca }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->marca }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("marca/$model->codmarca") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->marca }}'?" data-after="location.replace('{{ url('marca') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
    <script type="text/javascript">
        $(document).ready(function() {
            $( "#delete-imagem" ).click(function() {
                swal({
                    title: "Tem certeza que deseja excluir essa imagem?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if (isConfirm) {
                        location.replace(baseUrl + "/imagem/delete/?model=marca&id={{$model->codmarca}}");
                    } 
                });
            });    
        });    
    </script>
@endsection
@stop