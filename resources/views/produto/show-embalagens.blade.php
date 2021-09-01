<div id="div-embalagens">


  <!-- Unidade -->
  <div class="col-lg-2 col-md-3">
    <div class="card">
      <img class="card-img-top img-fluid" src="{{ empty($model->codprodutoimagem)?URL::asset('public/imagens/semimagem.jpg'):URL::asset("public/imagens/{$model->ProdutoImagem->Imagem->arquivo}") }}" id="imagem-principal">
      <div class="card-block">
        <h5 class="card-title">
          {{ $model->UnidadeMedida->sigla }} 
          <span class="pull-right"> 
            <small class="text-muted">R$</small> 
            {{ formataNumero($model->preco) }}
          </span>
        </h5>
        <div class="btn-group pull-right">
            <div data-toggle="modal" data-target=".modal-alterar-imagem-padrao">
                <button class="btn btn-sm btn-secondary waves-effect btn-alterar-imagem-padrao" title="Alterar Imagem Padrão" data-toggle="tooltip"><i class="fa fa-image"></i></button>
            </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>

  @foreach($model->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe)
  <!-- Embalagem {{ $pe->codprodutoembalagem }} -->
  <div class="col-lg-2 col-md-3">
    <div class="card">
      <img class="card-img-top img-fluid" src="{{ empty($pe->codprodutoimagem)?URL::asset('public/imagens/semimagem.jpg'):URL::asset("public/imagens/{$pe->ProdutoImagem->Imagem->arquivo}") }}" id="imagem-embalagem-{{ $pe->codprodutoembalagem}}">
      <div class="card-block">
        <h5 class="card-title">
          {{ $pe->UnidadeMedida->sigla }} 
          C/{{ formataNumero($pe->quantidade, 0) }}
          <span class="pull-right"> 
            <small class="text-muted">R$</small> 
            @if (empty($pe->preco))
            <i class="text-muted"><small>
                ({{ formataNumero($model->preco * $pe->quantidade) }})
              </small></i>
            @else
            {{ formataNumero($pe->preco) }}                            
            @endif
          </span>
        </h5>
        <div class="btn-group pull-right">
            <a class="btn btn-sm btn-secondary waves-effect" title="Editar Embalagem" data-toggle="tooltip" href="{{ url("produto-embalagem/$pe->codprodutoembalagem/edit") }}"><i class="fa fa-pencil"></i></a>
            <a class="btn btn-sm btn-secondary waves-effect" title="Excluir Embalagem" data-toggle="tooltip" href="{{ url("produto-embalagem/$pe->codprodutoembalagem") }}" data-delete data-question="Tem certeza que deseja excluir a Embalagem '{{ $pe->UnidadeMedida->unidademedida }} com {{ formataNumero($pe->quantidade, 0) }}'?" data-after="recarregaDiv('div-embalagens')"><i class="fa fa-trash"></i></a>
            <div class="btn btn-sm btn-secondary waves-effect " data-toggle="modal" data-target=".modal-alterar-imagem-padrao">
                <a class="btn-alterar-imagem-padrao" title="Alterar Imagem Padrão" data-toggle="tooltip" data-codprodutoembalagem="{{ $pe->codprodutoembalagem }}"><i class="fa fa-image"></i></a>
            </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  @endforeach

</div>

