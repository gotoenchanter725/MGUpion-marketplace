@extends('layouts.default')
@section('content')
<?php $imagens = $model->ImagemS()->orderBy('ordem')->get(); ?>

<div class="col-md-2 pull-right" >
    <div class="row">
        <div class="card" style="position: sticky">
            <div class="card-block">
                <ul class="nav nav-pills nav-stacked">
                  <li class="nav-item">
                    <a class="nav-link" href="#secao-embalagens">Embalagens</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#secao-variacoes">Variações</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#secao-movimentacao">Movimentação</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#secao-imagens">Imagens</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#secao-site">Site</a>
                  </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row" id="secao-embalagens">
    @include('produto.show-embalagens') 
</div>

<div class="row" id="secao-variacoes">
    @include('produto.show-variacoes')
</div>

<div class='card' id="secao-movimentacao">
    <h4 class="card-header">Movimentação</h4>
    <div class='card-block'>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-estoque" role="tab">Estoque</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-negocio" role="tab">Negócios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-notasfiscais" role="tab">Notas</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane" id="tab-estoque" role="tabpanel">
            <div id="div-estoque">
              <b>Aguarde...</b>
            </div>                        
          </div>
          <div role="tabpanel" class="tab-pane fade" id="tab-negocio">
            <div class="collapse" id="collapseNegocios">
              <div class='well well-sm'>
                {!! Form::model(Request::session()->get('MGLara.Http.Controllers.NegocioProdutoBarraController.filtros'), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'class' => 'form-horizontal','id' => 'produto-negocio-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      {!! Form::label('negocio_lancamento_de', 'De') !!}
                      {!! Form::date('negocio_lancamento_de', null, ['class' => 'form-control', 'id' => 'negocio_lancamento_de', 'placeholder' => 'De']) !!}
                    </div> 
                  </div> 
                  <div class="col-md-4">
                    <div class="form-group">
                      {!! Form::label('negocio_lancamento_ate', 'Até') !!}
                      {!! Form::date('negocio_lancamento_ate', null, ['class' => 'form-control', 'id' => 'negocio_lancamento_ate', 'placeholder' => 'Até']) !!}
                    </div> 
                  </div> 
                  <div class="col-md-4">
                    <div class="form-group">
                      {!! Form::label('negocio_codfilial', 'Filial') !!}
                      {!! Form::select2Filial('negocio_codfilial', null, ['style'=>'width:100%', 'id'=>'negocio_codfilial']) !!}
                    </div> 
                  </div> 
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      {!! Form::label('negocio_codpessoa', 'Pessoa') !!}
                      {!! Form::select2Pessoa('negocio_codpessoa', null, ['class' => 'form-control', 'id'=>'negocio_codpessoa', 'style'=>'width:100%', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!}
                    </div>                            
                  </div>                            
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('negocio_codnaturezaoperacao', 'Natureza de Operação') !!}
                      {!! Form::select2NaturezaOperacao('negocio_codnaturezaoperacao', null, ['style'=>'width:100%', 'id' => 'negocio_codnaturezaoperacao']) !!}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('negocio_codprodutovariacao', 'Variação') !!}
                      {!! Form::select2ProdutoVariacao('negocio_codprodutovariacao', null, ['style'=>'width:100%', 'id' => 'negocio_codprodutovariacao', 'codproduto'=>'negocio_codproduto']) !!}
                    </div>
                  </div>
                </div>
                {!! Form::hidden('negocio_codproduto', $model->codproduto, ['id'=>'negocio_codproduto']) !!}
                {!! Form::close() !!}
              </div>
            </div>
            <br>
            <div id="div-negocios" class="table-responsive">
              <a class='btn btn-sm btn-secondary waves-effect' href='#collapseNegocios' data-toggle='collapse' aria-expanded='false' aria-controls='collapseNegocios'><i class='fa fa-search'></i></a>
              @include('layouts.includes.datatable.html', ['id' => 'negocios', 'colunas' => ['URL', 'Negócio', 'Lançamento', 'Pessoa', 'Operação', 'Filial', 'Variação', 'Valor', 'QTD']])
              <div class="clearfix"></div>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="tab-notasfiscais">
            <div class="collapse" id="filtro-notasfiscais">
              <div class='well well-sm'>
                {!! Form::model(Request::session()->get('MGLara.Http.Controllers.NotaFiscalProdutoBarraController.filtros'), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'class' => 'form-horizontal', 'method' => 'GET', 'id' => 'produto-notasfiscais-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      {!! Form::label('notasfiscais_lancamento_de', 'De') !!}ProdutoImagemS
                      {!! Form::date('notasfiscais_lancamento_de', null, ['class' => 'form-control', 'id' => 'notasfiscais_lancamento_de', 'placeholder' => 'De']) !!}
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      {!! Form::label('notasfiscais_lancamento_ate', 'Até') !!}
                      {!! Form::date('notasfiscais_lancamento_ate', null, ['class' => 'form-control', 'id' => 'notasfiscais_lancamento_ate', 'placeholder' => 'Até']) !!}
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      {!! Form::label('notasfiscais_codfilial', 'Filial') !!}
                      {!! Form::select2Filial('notasfiscais_codfilial', null, ['style'=>'width:100%', 'id'=>'notasfiscais_codfilial']) !!}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      {!! Form::label('notasfiscais_codpessoa', 'Pessoa') !!}
                      {!! Form::select2Pessoa('notasfiscais_codpessoa', null, ['class' => 'form-control','id'=>'notasfiscais_codpessoa', 'style'=>'width:100%', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!}
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('notasfiscais_codnaturezaoperacao', 'Natureza de Operação') !!}
                      {!! Form::select2NaturezaOperacao('notasfiscais_codnaturezaoperacao', null, ['style'=>'width:100%', 'id' => 'notasfiscais_codnaturezaoperacao']) !!}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('notasfiscais_codnaturezaoperacao', 'Variação') !!}
                      {!! Form::select2ProdutoVariacao('notasfiscais_codprodutovariacao', null, ['style'=>'width:100%', 'id' => 'notasfiscais_codprodutovariacao', 'codproduto'=>'notasfiscais_codproduto']) !!}
                    </div>
                  </div>
                  {!! Form::hidden('notasfiscais_codproduto', $model->codproduto, ['id'=>'notasfiscais_codproduto']) !!}
                  {!! Form::hidden('_div', 'div-notasfiscais', ['id'=>'notasfiscais_page']) !!}
                  {!! Form::close() !!}
                </div>
              </div>
            </div>
            <br>
            <div id="div-notasfiscais" class="table-responsive">
              <a class='btn btn-sm btn-secondary waves-effect' href='#filtro-notasfiscais' data-toggle='collapse' aria-expanded='false' aria-controls='filtro-notasfiscais'><i class='fa fa-search'></i></a>
              @include('layouts.includes.datatable.html', ['id' => 'notas', 'colunas' => ['URL', 'Nota', 'Lançamento', 'Pessoa', 'Operação', 'Filial', 'Variação', 'Valor', 'QTD']])
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
    </div>    
</div>


<div class="row" id="secao-imagens">
  
  <div class="col-md-6">
    <div class='card'>
      <h4 class="card-header">
        Imagens
        <div class="btn-group">
            <a class="btn btn-sm btn-secondary waves-effect" data-toggle="tooltip" href="{{ url("/imagem/create?codproduto=$model->codproduto") }}" title="Cadastrar imagem">
              <i class="fa fa-plus"></i> 
            </a>
            <div class="btn btn-sm btn-secondary waves-effect" data-toggle="modal"  data-target=".modal-alterar-imagem-ordem">
                <a title="Alterar ordem"data-toggle="tooltip"><i class="fa fa-retweet"></i></a>
            </div>
        </div>


      </h4>
      <div class='card-block'>
        @include('produto.show-imagens')
      </div>
    </div>

  </div>

  <div class="col-md-6">
    <div class='card'>
      <h4 class="card-header">Fiscal</h4>
      <div class='card-block'>
        <ol class="breadcrumb" style="margin: 0 0 15px 0">
          {!! 
          titulo(
          NULL, 
          [
          url("secao-produto/{$model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}") => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
          url("familia-produto/{$model->SubGrupoProduto->GrupoProduto->codfamiliaproduto}") => $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto,
          url("grupo-produto/{$model->SubGrupoProduto->codgrupoproduto}") => $model->SubGrupoProduto->GrupoProduto->grupoproduto,
          url("sub-grupo-produto/{$model->codsubgrupoproduto}") => $model->SubGrupoProduto->subgrupoproduto,
          url("marca/{$model->codmarca}") => $model->Marca->marca,
          $model->referencia,
          ], 
          NULL) 
          !!}
        </ol>
        <ol class="breadcrumb">
          <?php
              $arr = [
                  url("tipo-produto/{$model->codtipoproduto}") => $model->TipoProduto->tipoproduto,
                  url("ncm/{$model->codncm}") => formataNcm($model->Ncm->ncm),
                  url("tributacao/{$model->codtributacao}") => $model->Tributacao->tributacao,
              ];

              if (!empty($model->codcest))
                  $arr[url("cest/{$model->codcest}")] = formataCest($model->Cest->cest);

              $arr[] = ($model->importado) ? 'Importado' : 'Nacional';
          ?>
          {!! 
          titulo(NULL, $arr, NULL) 
          !!}
        </ol>
        <br>
        @include('produto.show-ncm')
        {!! $model->observacoes !!}
          
      </div>
        
    </div>
  </div>
</div>

<div id="secao-site">
  @include('produto.show-site')
</div>


<!--  Modal content for the above example -->
<div class="modal fade modal-alterar-imagem-padrao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
        <h4 class="modal-title" id="myLargeModalLabel">Alterar Imagem Padrão</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          @foreach ($imagens as $imagem)
          <div class="col-md-3">
            <div class=" thumb">
              <a href="#" class="btn-alterar-imagem-padrao-salvar" data-codimagem="{{ $imagem->codimagem }}" data-dismiss="modal" >
                <img src="{{ asset("public/imagens/$imagem->arquivo") }}" class="thumb-img">
              </a>
            </div>
          </div>
          @endforeach 
        </div>
      </div>
    </div>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--  Modal content for the above example -->
<div class="modal fade modal-alterar-imagem-ordem" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
        <h4 class="modal-title" id="myLargeModalLabel">Alterar Ordem de Exibição das Imagens</h4>
      </div>
      <div class="modal-body">
        <div class="row" id="sortable">
          @foreach ($imagens as $imagem)
          <div class="col-md-2" id="codimagem-{{ $imagem->codimagem }}">
            <div class="thumb">
              <a href="#" data-codimagem="{{ $imagem->codimagem }}">
                <img src="{{ asset("public/imagens/$imagem->arquivo") }}" class="thumb-img">
              </a>
            </div>
          </div>
          @endforeach 
        </div>
      </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-modal-alterar-imagem-ordem-salvar">Salvar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

<!-- /.modal-content -->

@section('buttons')

    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("produto/$model->codproduto/edit") }}" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil"></i></a>
    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("produto/create/?duplicar={$model->codproduto}") }}" data-toggle="tooltip" title="Duplicar"><i class="fa fa-copy"></i></a>    
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("produto/$model->codproduto/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')" data-toggle="tooltip" title="Inativar"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("produto/$model->codproduto/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->codproduto }}'?" data-after="recarregaDiv('content-page')" data-toggle="tooltip" title="Ativar"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("produto/$model->codproduto") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->codproduto }}'?" data-after="location.replace('{{ url('produto') }}');" data-toggle="tooltip" title="Excluir"><i class="fa fa-trash"></i></a>
    
    <a class="btn btn-sm btn-secondary waves-effect" href="<?php echo url("produto-embalagem/create?codproduto={$model->codproduto}"); ?>" data-toggle="tooltip" title="Criar nova Embalagem">Embalagem <i class="fa fa-plus"></i></a>
    
    <a href="<?php echo url("produto-variacao/create?codproduto={$model->codproduto}"); ?>" class="btn btn-sm btn-secondary waves-effect" data-toggle="tooltip" title="Criar nova Variação"> Variação <span class="fa fa-plus"></span></a>
    <a href="<?php echo url("produto/$model->codproduto/transferir-variacao"); ?>" class="btn btn-sm btn-secondary waves-effect" data-toggle="tooltip" title="Transferir Variação para outro Produto"> Variação <span class="fa fa-exchange"></span></a>
    <a href="<?php echo url("produto-barra/create?codproduto={$model->codproduto}"); ?>" class="btn btn-sm btn-secondary waves-effect" data-toggle="tooltip" title="Criar novo Código de Barras"> Barras <span class="fa fa-plus"></span></a>
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<style type="text/css">
    .nav-tabs .nav-link {
        padding: 0.5em 0.7em !important;
    }
    .nav-tabs {
        margin-bottom: 1rem !important;
    }
</style>
@include('layouts.includes.datatable.assets')
<link href="{{ URL::asset('public/assets/css/bootstrap-alpha6-carousel.css') }}" rel="stylesheet" type="text/css"/>
<script src="{{ URL::asset('public/assets/plugins/jquery-ui/ui/core.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/jquery-ui/ui/widget.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/jquery-ui/ui/mouse.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/jquery-ui/ui/sortable.js') }}"></script>
<script type="text/javascript">
function mostraListagemNegocios()
{
    var datable_negocios = $('#negocios').DataTable({
        dom: 'rtpi',
        pageLength: 10,
        language: {
            url: "{{ URL::asset('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
        },
        processing: true,
        serverSide: true,
        order: [
            [ 2, 'DESC'],
        ],        
        ajax: {
            url: "{{ url('negocio-produto-barra/datatable') }}",
            data: function ( d ) {
                d.filtros = new Object;
                    d.filtros.negocio_lancamento_de         = $('#negocio_lancamento_de').val();
                    d.filtros.negocio_lancamento_ate        = $('#negocio_lancamento_ate').val();
                    d.filtros.negocio_codfilial             = $('#negocio_codfilial').val();
                    d.filtros.negocio_codnaturezaoperacao   = $('#negocio_codnaturezaoperacao').val();
                    d.filtros.negocio_codprodutovariacao    = $('#negocio_codprodutovariacao').val();
                    d.filtros.negocio_codpessoa             = $('#negocio_codpessoa').val();
                    d.filtros.negocio_codproduto            = $('#negocio_codproduto').val();
                }
        },
        lengthChange: false,
        columnDefs: [
            {
                targets: [0],
                visible: false,
            },
            {
                render: function ( data, type, row ) {
                    return '<a href="' + row[0] + '">' + data +'</a>';
                },
                targets: 1
            }
        ],
        initComplete: function(settings, json) {
            datable_negocios.buttons().container().appendTo('#negocios_wrapper .col-md-12:eq(0)');
            $('#negocios_paginate, #negocios_info').addClass('col-md-12');
            $('ul.pagination').addClass('pull-left');
        }
    });

    $('#negocio_lancamento_de').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_lancamento_ate').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codfilial').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codnaturezaoperacao').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codprodutovariacao').change(function() {
        datable_negocios.ajax.reload();
    });
    
    $('#negocio_codpessoa').change(function() {
        datable_negocios.ajax.reload();
    });
}

function mostraListagemNotasFiscais()
{
    var datable_notas = $('#notas').DataTable({
        dom: 'rtpi',
        pageLength: 10,
        language: {
            url: "{{ URL::asset('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
        },
        processing: true,
        serverSide: true,
        order: [
            [ 2, 'DESC'],
        ],        
        ajax: {
            url: "{{ url('nota-fiscal-produto-barra/datatable') }}",
            data: function ( d ) {
                d.filtros = new Object;
                    d.filtros.notasfiscais_lancamento_de         = $('#notasfiscais_lancamento_de').val();
                    d.filtros.notasfiscais_lancamento_ate        = $('#notasfiscais_lancamento_ate').val();
                    d.filtros.notasfiscais_codfilial             = $('#notasfiscais_codfilial').val();
                    d.filtros.notasfiscais_codnaturezaoperacao   = $('#notasfiscais_codnaturezaoperacao').val();
                    d.filtros.notasfiscais_codprodutovariacao    = $('#notasfiscais_codprodutovariacao').val();
                    d.filtros.notasfiscais_codpessoa             = $('#notasfiscais_codpessoa').val();
                    d.filtros.notasfiscais_codproduto            = $('#notasfiscais_codproduto').val();
                }
        },
        lengthChange: false,
        columnDefs: [
            {
                targets: [0],
                visible: false,
            },
            {
                render: function ( data, type, row ) {
                    return '<a href="' + row[0] + '">' + data +'</a>';
                },
                targets: 1
            }
        ],
        initComplete: function(settings, json) {
            datable_notas.buttons().container().appendTo('#notas_wrapper .col-md-12:eq(0)');
            $('#notas_paginate, #notas_info').addClass('col-md-12');
            $('ul.pagination').addClass('pull-left');
        }
    });

    $('#notasfiscais_lancamento_de').change(function() {
        datable_notas.ajax.reload();
    });
    
    $('#notasfiscais_lancamento_ate').change(function() {
        datable_notas.ajax.reload();
    });
    
    $('#notasfiscais_codfilial').change(function() {
        datable_notas.ajax.reload();
    });
    
    $('#notasfiscais_codnaturezaoperacao').change(function() {
        datable_notas.ajax.reload();
    });
    
    $('#notasfiscais_codprodutovariacao').change(function() {
        datable_notas.ajax.reload();
    });
    
    $('#notasfiscais_codpessoa').change(function() {
        datable_notas.ajax.reload();
    });
}

function confirmaAlteracaoImagemPadrao(link) {
    swal({
        title: "Tem certeza que deseja alterar a imagem Padrão?",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm){
        if (isConfirm) {
            alterarImagemPadrao(link)
        }
    });
}

function alterarImagemPadrao(link) {
    $.ajax({
        type: 'PATCH',
        url: "{{ url("produto/{$model->codproduto}/alterar-imagem-padrao") }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            codimagem: $(link).data('codimagem'),
            codprodutoembalagem: codprodutoembalagem,
            codprodutovariacao: codprodutovariacao,
        },
    })
    .done(function (data) {
        
        var src = $('img', link).attr('src');

        var imagem = '#imagem-principal';
        if (codprodutoembalagem != undefined) {
            imagem = '#imagem-embalagem-' + codprodutoembalagem;
        } else if (codprodutovariacao != undefined) {
            imagem = '#imagem-variacao-' + codprodutovariacao;
        }
        $(imagem).attr('src', src);
        
        toastr['success']('Imagem padrão alterada!');
    })
    .fail(function (XHR) {
        toastr['error'](XHR.status + ' ' + XHR.statusText);
    });  
}

function alterarImagemOrdem(ordem) {
    $.ajax({
        type: 'PATCH',
        url: "{{ url("produto/{$model->codproduto}/alterar-imagem-ordem") }}",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: ordem,
    })
    .done(function (data) {
        toastr['success']('Ordem de exibição das imagens alterada!');
    })
    .fail(function (XHR) {
        toastr['error'](XHR.status + ' ' + XHR.statusText);
    });    
}


var codprodutoembalagem = null;
var codprodutovariacao = null;

$(document).ready(function() {
    $('.btn-alterar-imagem-padrao').click(function (e) {
        codprodutoembalagem = $(this).data('codprodutoembalagem');
        codprodutovariacao = $(this).data('codprodutovariacao');
        console.log('codprodutoembalagem = ' + codprodutoembalagem);
        console.log('codprodutovariacao = ' + codprodutovariacao);
    });
    
    $('.btn-alterar-imagem-padrao-salvar').click(function (e) {
        e.preventDefault();
        confirmaAlteracaoImagemPadrao(this);
    });
    
    var listagemNegocioAberta = false;
    $('a[href="#tab-negocio"]').on('shown.bs.tab', function (e) {
        if (!listagemNegocioAberta)
            mostraListagemNegocios();
        listagemNegocioAberta = true;
    });
    
    var listagemNotasFiscaisAberta = false;
    $('a[href="#tab-notasfiscais"]').on('shown.bs.tab', function (e) {
        if (!listagemNotasFiscaisAberta)
            mostraListagemNotasFiscais();
        listagemNotasFiscaisAberta = true;
    });

    var listagemEstoqueAberta = false;
    $('a[href="#tab-estoque"]').on('shown.bs.tab', function (e) {
        recarregaDiv('div-estoque');
        listagemEstoqueAberta = true;
    });
    
    $('#sincronizar').hide();
    $('#integracao-open-cart').click(function (e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja sincronizar esse produto?",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('produto/sincroniza-produto-open-cart') }}",
                    data: {
                        id:{{ $model->codproduto }}
                    },
                    beforeSend: function( xhr ) {
                        $('#sincronizar').show(function() {
                            $('#integracao-open-cart').attr('disabled','disabled');
                        });
                    }
                })
                .done(function (data) {
                    $('#sincronizar').hide(function() {
                        $('#integracao-open-cart').removeAttr('disabled');
                    });
                    if(data.resultado === true) {
                        swal({
                            title: 'Sucesso!',
                            text: data.mensagem,
                            type: 'success',
                        });                        
                    } else {
                        swal({
                            title: 'Sucesso!',
                            text: data.mensagem,
                            type: 'error',
                        });                        
                    }
                })
                .fail(function (data) {
                    console.log('erro no POST');
                });  
            } 
        });         
    }); 
    
    $( ".delete-imagem" ).click(function() {
        var codimagem = $(this).data("codimagem");
        swal({
            title: "Tem certeza que deseja excluir essa imagem?",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                location.replace(baseUrl + "/imagem/delete/" + codimagem);
            } 
        });
    });    

    $('#sortable').sortable();

    $('#btn-modal-alterar-imagem-ordem-salvar').on('click', function(e){ // trigger function on save button click
        var sortable_data = $('#sortable').sortable('serialize'); // serialize data from ul#sortable
        alterarImagemOrdem(sortable_data);
    });

});

</script>
@endsection

@stop
