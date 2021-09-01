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
              <td>{{ $model->codimagem }}</td> 
            </tr>
            <tr> 
              <th>Imagem</th> 
              <td>{{ $model->codimagem }}</td> 
            </tr>
            <tr> 
              <th>Observacoes</th> 
              <td>{{ $model->observacoes }}</td> 
            </tr>
            <tr> 
              <th>Arquivo</th> 
              <td>{{ $model->arquivo }}</td> 
            </tr>
          </tbody> 
        </table>
        <div class='clearfix'></div>
      </div>
    </div>
    <div class='card'>
      <h4 class="card-header">Relacionamentos</h4>
      <div class='card-block'>
        @foreach($model->GrupoProdutoS as $grupo)
        <p>
          <strong>Grupo:</strong> <a href="{{ url("grupo-produto/{$grupo->codgrupoproduto}") }}">{{ $grupo->grupoproduto }}</a>
        </p>
        @endforeach

        @foreach($model->MarcaS as $marca)
        <p>
          <strong>Marca:</strong> <a href="{{ url("marca/{$marca->codmarca}") }}">{{ $marca->marca }}</a>
        </p>
        @endforeach

        @foreach($model->SecaoProdutoS as $secao)
        <p>
          <strong>Seçao Produto:</strong> <a href="{{ url("secao-produto/{$secao->codsecaoproduto}") }}">{{ $secao->secaoproduto }}</a>
        </p>
        @endforeach

        @foreach($model->FamiliaProdutoS as $familia)
        <p>
          <strong>Família Produto:</strong> <a href="{{ url("familia-produto/{$familia->codfamiliaproduto}") }}">{{ $familia->familiaproduto }}</a>
        </p>
        @endforeach

        @foreach($model->SubGrupoProdutoS as $subgrupo)
        <p>
          <strong>Sub Grupo:</strong> <a href="{{ url("sub-grupo-produto/{$subgrupo->codsubgrupoproduto}") }}">{{ $subgrupo->subgrupoproduto }}</a>
        </p>
        @endforeach

        @foreach($model->ProdutoS as $produto)
        <p>
          <strong>Produto:</strong>  <a href="{{ url("produto/{$produto->codproduto}") }}">{{ $produto->produto }}</a>
        </p>
        @endforeach
      </div>
    </div>
  </div>
  <div class='col-md-6'>
    <div class='card'>
      <h4 class="card-header">Imagem</h4>
      <div class='card-block text-center'>
        <a href="{{ $model->url }}" target="_blank">
          <img class="img-fluid" style="margin: 0 auto" src="{{ $model->url }}">
        </a>
      </div>
    </div>
  </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("imagem/$model->codimagem/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("imagem/$model->codimagem/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codimagem }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("imagem/$model->codimagem/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codimagem }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("imagem/$model->codimagem") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codimagem }}'?" data-after="location.replace('{{ url('imagem') }}');"><i class="fa fa-trash"></i></a>                
    
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