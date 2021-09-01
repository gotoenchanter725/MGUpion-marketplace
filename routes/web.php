<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

/* Acessar da rede interna sem autenticacao, ou da rede externa com autenticacao */
Route::group(['middleware' => 'redeconfiavel'], function() {
    Route::get('produto/consulta/{barras}', 'ProdutoController@consulta');
    Route::get('produto/quiosque', 'ProdutoController@quiosque');
    Route::get('produto-barra/select2', 'ProdutoBarraController@select2');
    
    /* Prancheta */
    Route::get('prancheta/quiosque/{codestoquelocal?}', 'PranchetaController@quiosque');
    Route::get('prancheta/quiosque/produto/{codpranchetaproduto}/{codestoquelocal?}', 'PranchetaController@quiosqueProduto');
});    
    
    
Route::group(['middleware' => 'auth'], function() {
    
    Route::get('/', 'InicialController@inicial');

    /* select2 */
    Route::get('pessoa/select2', 'PessoaController@select2');
    Route::get('filial/select2', 'FilialController@select2');
    Route::get('marca/select2', 'MarcaController@select2');
    Route::get('grupo-produto/select2', 'GrupoProdutoController@select2');
    Route::get('ncm/select2', 'NcmController@select2');
    Route::get('cest/select2', 'CestController@select2');
    Route::get('sub-grupo-produto/select2', 'SubGrupoProdutoController@select2');
    Route::get('produto/select2', 'ProdutoController@select2');
    Route::get('produto-variacao/select2', 'ProdutoVariacaoController@select2');
    Route::get('cidade/select2', 'CidadeController@select2');
    Route::get('familia-produto/select2', 'FamiliaProdutoController@select2');
    

    /* Marca */
    Route::resource('marca/inativar', 'MarcaController@inativar');
    Route::resource('marca/{id}/busca-codproduto', 'MarcaController@buscaCodproduto');
    Route::resource('marca', 'MarcaController');

    /* Seção Produto */
    Route::post('secao-produto/inativar', 'SecaoProdutoController@inativar');
    Route::resource('secao-produto', 'SecaoProdutoController');

    /* Família Produto */
    Route::post('familia-produto/inativar', 'FamiliaProdutoController@inativar');
    Route::resource('familia-produto', 'FamiliaProdutoController');

    /* GrupoProduto */
    Route::post('grupo-produto/inativar', 'GrupoProdutoController@inativar');
    Route::resource('grupo-produto/{id}/busca-codproduto', 'GrupoProdutoController@buscaCodproduto');
    Route::resource('grupo-produto', 'GrupoProdutoController');
    
    /* SubGrupoProduto */
    Route::resource('sub-grupo-produto/{id}/busca-codproduto', 'SubGrupoProdutoController@buscaCodproduto');
    Route::get('sub-grupo-produto/datatable-produto', 'SubGrupoProdutoController@datatableProduto');  
    Route::resource('sub-grupo-produto', 'SubGrupoProdutoController');  
    
    /* Tipos de produto */
    Route::resource('tipo-produto', 'TipoProdutoController');

    /* Unidades de medida */
    Route::resource('unidade-medida', 'UnidadeMedidaController');
    
    /* NCM */
    Route::resource('ncm', 'NcmController');

    /* Usuários */
    Route::get('usuario/datatable', 'UsuarioController@datatable');
    Route::get('usuario/mudar-senha', 'UsuarioController@mudarSenha');
    Route::post('usuario/mudar-senha-update', 'UsuarioController@mudarSenhaUpdate');
    Route::get('usuario/{id}/grupos', 'UsuarioController@grupos');
    Route::post('usuario/{id}/grupos', 'UsuarioController@gruposCreate');
    Route::delete('usuario/{id}/grupos', 'UsuarioController@gruposDestroy');
    Route::resource('usuario', 'UsuarioController');

    /* Grupos de usuários */
    Route::put('grupo-usuario/{id}/ativar', 'GrupoUsuarioController@ativar');
    Route::put('grupo-usuario/{id}/inativar', 'GrupoUsuarioController@inativar');
    Route::get('grupo-usuario/datatable', 'GrupoUsuarioController@datatable');
    Route::resource('grupo-usuario', 'GrupoUsuarioController');

    /* Banco */
    Route::resource('banco', 'BancoController');
    
    /* Pais */
    Route::resource('pais', 'PaisController');
    
    /* permissao */
    Route::delete('permissao', 'PermissaoController@destroyPermissao');
    Route::resource('permissao', 'PermissaoController', ['only' => ['index', 'store']]);
    
    /* estado civil */
    Route::resource('estado-civil', 'EstadoCivilController');    
    
    /* Feriados */
    Route::resource('feriado', 'FeriadoController');
    
    /* Vale Compras */    
    Route::get('vale-compra/{id}/imprimir', 'ValeCompraController@imprimir');
    Route::resource('vale-compra', 'ValeCompraController');    
    
    
    /* Modelos de Vale Compras */    
    Route::resource('vale-compra-modelo', 'ValeCompraModeloController'); 
    
    /* Cargos */    
    Route::resource('cargo', 'CargoController');

    /* Histórico de preços */    
    Route::resource('produto-historico-preco', 'ProdutoHistoricoPrecoController');
    
    /* EstoqueLocal */
    Route::resource('estoque-local', 'EstoqueLocalController');

    /* EstoqueMes */
    Route::get('kardex/{codestoquelocal}/{codprodutovariacao}/{fiscal}/{ano}/{mes}', 'EstoqueMesController@kardex');
    Route::get('estoque-mes/{id}', 'EstoqueMesController@show');
    

    /* EstoqueSaldo */
    Route::get('estoque-saldo/relatorio-analise-filtro', 'EstoqueSaldoController@relatorioAnaliseFiltro');
    Route::get('estoque-saldo/relatorio-analise', 'EstoqueSaldoController@relatorioAnalise');
    Route::get('estoque-saldo/relatorio-comparativo-vendas-filtro', 'EstoqueSaldoController@relatorioComparativoVendasFiltro');
    Route::get('estoque-saldo/relatorio-comparativo-vendas', 'EstoqueSaldoController@relatorioComparativoVendas');
    Route::get('estoque-saldo/relatorio-fisico-fiscal-filtro', 'EstoqueSaldoController@relatorioFisicoFiscalFiltro');
    Route::get('estoque-saldo/relatorio-fisico-fiscal', 'EstoqueSaldoController@relatorioFisicoFiscal');
    Route::resource('estoque-saldo', 'EstoqueSaldoController');
    Route::get('estoque-saldo/{id}/zera', 'EstoqueSaldoController@zera');
    
    /* EstoqueMovimento */
    Route::resource('estoque-movimento', 'EstoqueMovimentoController', ['only' => ['edit', 'update', 'create', 'store', 'destroy']]);
    
    /* Gerador de Codigo */
    Route::get('gerador-codigo/{tabela}/model', 'GeradorCodigoController@showModel');
    Route::post('gerador-codigo/{tabela}/model', 'GeradorCodigoController@storeModel');
    Route::get('gerador-codigo/{tabela}/repository', 'GeradorCodigoController@showRepository');
    Route::post('gerador-codigo/{tabela}/repository', 'GeradorCodigoController@storeRepository');
    Route::get('gerador-codigo/{tabela}/policy', 'GeradorCodigoController@showPolicy');
    Route::post('gerador-codigo/{tabela}/policy', 'GeradorCodigoController@storePolicy');
    Route::get('gerador-codigo/{tabela}/controller', 'GeradorCodigoController@showController');
    Route::post('gerador-codigo/{tabela}/controller', 'GeradorCodigoController@storeController');
    Route::get('gerador-codigo/{tabela}/view/index', 'GeradorCodigoController@showViewIndex');
    Route::post('gerador-codigo/{tabela}/view/index', 'GeradorCodigoController@storeViewIndex');
    Route::get('gerador-codigo/{tabela}/view/show', 'GeradorCodigoController@showViewShow');
    Route::post('gerador-codigo/{tabela}/view/show', 'GeradorCodigoController@storeViewShow');
    Route::get('gerador-codigo/{tabela}/view/create', 'GeradorCodigoController@showViewCreate');
    Route::post('gerador-codigo/{tabela}/view/create', 'GeradorCodigoController@storeViewCreate');
    Route::get('gerador-codigo/{tabela}/view/edit', 'GeradorCodigoController@showViewEdit');
    Route::post('gerador-codigo/{tabela}/view/edit', 'GeradorCodigoController@storeViewEdit');
    Route::get('gerador-codigo/{tabela}/view/form', 'GeradorCodigoController@showViewForm');
    Route::post('gerador-codigo/{tabela}/view/form', 'GeradorCodigoController@storeViewForm');
    Route::resource('gerador-codigo', 'GeradorCodigoController', ['only' => ['index', 'show']]);
    //Route::resource('gerador-codigo','GeradorCodigoController');
    
    /* Cheques */
    Route::resource('cheque-motivo-devolucao', 'ChequeMotivoDevolucaoController');
    Route::resource('cheque', 'ChequeController');
    Route::get('cheque/consulta/{cmc7}', 'ChequeController@consulta');
    Route::get('cheque/consultaemitente/{cnpj}', 'ChequeController@consultaemitente');
    
    Route::resource('cheque-repasse', 'ChequeRepasseController');
    Route::post('cheque-repasse/consulta', 'ChequeRepasseController@consulta');
    
    /* Dominio */
    Route::get('dominio', 'DominioController@index');
    Route::post('dominio/exporta-estoque', 'DominioController@exportaEstoque');
    
    /* Estoque */
    Route::get('estoque-saldo/relatorio-analise-filtro', 'EstoqueSaldoController@relatorioAnaliseFiltro');
    Route::get('estoque-saldo/relatorio-analise', 'EstoqueSaldoController@relatorioAnalise');
    Route::get('estoque-saldo/relatorio-comparativo-vendas-filtro', 'EstoqueSaldoController@relatorioComparativoVendasFiltro');
    Route::get('estoque-saldo/relatorio-comparativo-vendas', 'EstoqueSaldoController@relatorioComparativoVendas');
    Route::get('estoque-saldo/relatorio-fisico-fiscal-filtro', 'EstoqueSaldoController@relatorioFisicoFiscalFiltro');
    Route::get('estoque-saldo/relatorio-fisico-fiscal', 'EstoqueSaldoController@relatorioFisicoFiscal');

    /* Conferencia de saldo de estoque */
    Route::get('estoque-saldo-conferencia/saldos', 'EstoqueSaldoConferenciaController@saldos');
    Route::resource('estoque-saldo-conferencia', 'EstoqueSaldoConferenciaController', ['only' => ['index', 'datatable', 'show', 'activate', 'inactivate', 'create', 'store']]);
    
    /* Tipo Movimento Título */
    Route::resource('tipo-movimento-titulo', 'TipoMovimentoTituloController');

    /* Meta */
    Route::resource('meta', 'MetaController');

    /* Produto */
    Route::patch('produto/{id}/site', 'ProdutoController@siteUpdate');
    Route::get('produto/{id}/site', 'ProdutoController@site');
    Route::patch('produto/{id}/alterar-imagem-padrao', 'ProdutoController@alterarImagemPadrao');
    Route::patch('produto/{id}/alterar-imagem-ordem', 'ProdutoController@alterarImagemOrdem');
    
    Route::get('produto/typeahead', 'ProdutoController@typeahead');
    Route::get('produto/sincroniza-produto-open-cart', 'ProdutoController@sincronizaProdutoOpenCart');
    Route::patch('produto/{id}/transferir-variacao', 'ProdutoController@transferirVariacaoSalvar');
    Route::get('produto/{id}/transferir-variacao', 'ProdutoController@transferirVariacao');
    
    Route::resource('produto', 'ProdutoController');
    Route::get('produto/{id}/unificar-barras', 'ProdutoController@unificarBarras');
    Route::post('produto/{id}/unificar-barras', 'ProdutoController@unificarBarrasSalvar');
    
    /* Negócio produto barra */
    Route::resource('negocio-produto-barra', 'NegocioProdutoBarraController');
    
    /* CEST */
    Route::resource('cest', 'CestController');

    /* Imagem */
    Route::delete('imagem/esvaziar-lixeira', 'ImagemController@esvaziarLixeira');
    Route::resource('imagem', 'ImagemController');
    
    /* Produto Barra */
    Route::resource('produto-barra', 'ProdutoBarraController');

    /* Produto Variação */
    Route::resource('produto-variacao', 'ProdutoVariacaoController');

    /* Prancheta */
    Route::get('prancheta/{id}/produto/{codproduto}/{codestoquelocal?}', 'PranchetaController@showProduto');
    Route::resource('prancheta', 'PranchetaController');
    Route::resource('prancheta-produto', 'PranchetaProdutoController');

    /* Nota Fiscal Produto Barra */
    Route::resource('nota-fiscal-produto-barra', 'NotaFiscalProdutoBarraController');
    
    /* Produto Embalagem */
    Route::resource('produto-embalagem', 'ProdutoEmbalagemController');
    
    /* Caixa */
    Route::resource('caixa', 'CaixaController');    
});
