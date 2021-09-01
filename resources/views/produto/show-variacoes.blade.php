<div id="div-variacoes">
  @foreach ($model->ProdutoVariacaoS()->orderByRaw("variacao asc nulls first")->get() as $pv)

  <!-- Variacao {{ $pv->codprodutovariacao }} -->
  <div class="col-md-12">
    <div class="card">
      <div class="row">


        <!-- Detalhes -->
        <div class="card-block">
        <!-- Imagem -->
        <div class="col-md-1">
          <img class="img-fluid" src="{{ empty($pv->codprodutoimagem)?URL::asset('public/imagens/semimagem.jpg'):URL::asset("public/imagens/{$pv->ProdutoImagem->Imagem->arquivo}") }}" id="imagem-variacao-{{ $pv->codprodutovariacao }}">
        </div>

          <!-- titulo -->
          <div class="col-md-4">

            <!-- botoes -->
            <div class="pull-right btn-group">
              <a class="btn btn-sm btn-secondary waves-effect" title="Editar Variação" data-toggle="tooltip" href="{{ url("produto-variacao/$pv->codprodutovariacao/edit") }}"><i class="fa fa-pencil"></i></a>
              <a class="btn btn-sm btn-secondary waves-effect" title="Excluir Variação" data-toggle="tooltip" href="{{ url("produto-variacao/$pv->codprodutovariacao") }}" data-delete data-question="Tem certeza que deseja excluir a variação '{{ $pv->variacao }}'?" data-after="recarregaDiv('div-variacoes');"><i class="fa fa-trash"></i></a>
              <div class="btn btn-sm btn-secondary waves-effect" data-toggle="modal" data-target=".modal-alterar-imagem-padrao">
                  <a class="btn-alterar-imagem-padrao"  title="Alterar Imagem Padrão" data-toggle="tooltip" data-codprodutovariacao="{{ $pv->codprodutovariacao }}"><i class="fa fa-image"></i></a>
              </div>
            </div>

            <!-- variacao --> 
            <h5 class="card-title text-truncate">
              @if (!empty($pv->variacao))
              {{ $pv->variacao }}
              @else
              <i class='text-muted'>{ Sem Variação }</i>
              @endif
              <div class="pull-right">
              </div>
            </h5>

            <!-- Referencia e marca -->
            <div class="card-subtitle text-truncate">
              <span class="text-muted">
                {{ $pv->referencia }}
              </span>
              @if (!empty($pv->codmarca))
              <br>
              <a href="{{ url("marca/$pv->codmarca") }}">
                {{ $pv->Marca->marca }}
              </a>
              @endif
            </div>

          </div>

          <!-- Barras -->
          <div class="col-md-7">
            <div class="row">
                <?php
                    $pbs = $pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
                            ->orderBy(DB::raw('coalesce(pe.quantidade, 0)'), 'ASC')
                            ->select([
                                'tblprodutobarra.codproduto',
                                'tblprodutobarra.codprodutobarra',
                                'tblprodutobarra.barras',
                                'tblprodutobarra.referencia',
                                'tblprodutobarra.codmarca',
                                'tblprodutobarra.codprodutoembalagem',
                                'tblprodutobarra.variacao',
                                'pe.quantidade',
                                'pe.codunidademedida',
                            ])
                            ->with('ProdutoEmbalagem')->get();
                    //dd($pbs);
                ?>            

              @foreach ($pbs as $pb)
              <div class="col-md-4 small text-truncate">
                <div class="pull-right btn-group">
                  <a class="btn btn-sm btn-secondary waves-effect" title="Editar Código de Barras" data-toggle="tooltip" href="{{ url("produto-barra/{$pb->codprodutobarra}/edit") }}"><i class="fa fa-pencil"></i></a>
                  <a class="btn btn-sm btn-secondary waves-effect" title="Excluir Código de Barras" data-toggle="tooltip" href="{{ url("produto-barra/{$pb->codprodutobarra}") }}" data-delete data-question="Tem certeza que deseja excluir o Código de Barras '{{ $pb->barras }}'?" data-after="recarregaDiv('div-variacoes');"><i class="fa fa-trash"></i></a>
                </div>
                {{ $pb->barras }}
                <br>
                <span class='text-muted'>
                  {{ $pb->referencia }}
                  {{ $pb->variacao }}
                  @if (!empty($pb->codprodutoembalagem))
                  {{ $pb->ProdutoEmbalagem->descricao }}
                  @else
                  {{ $model->UnidadeMedida->sigla }}
                  @endif
                </span>
              </div>
              @endforeach            
            </div>
          </div>


          <div class="clearfix"></div>
        </div>

      </div>
    </div>
  </div>

  @endforeach

</div>
