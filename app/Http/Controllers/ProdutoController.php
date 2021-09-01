<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use URL;

use MGLara\Http\Controllers\Controller;
use MGLara\Repositories\ProdutoRepository;
use MGLara\Repositories\ProdutoBarraRepository;
use MGLara\Repositories\ProdutoVariacaoRepository;
use MGLara\Repositories\ProdutoEmbalagemRepository;
use MGLara\Repositories\NegocioProdutoBarraRepository;
use MGLara\Repositories\NotaFiscalProdutoBarraRepository;
use MGLara\Models\TipoProduto;
use MGLara\Repositories\ProdutoHistoricoPrecoRepository;
use MGLara\Library\IntegracaoOpenCart\IntegracaoOpenCart;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

/**
 * @property  ProdutoRepository                     $repository
 * @property  ProdutoBarraRepository                $produtoBarraRepository 
 * @property  ProdutoVariacaoRepository             $produtoVariacaoRepository 
 * @property  ProdutoEmbalagemRepository            $produtoEmbalagemRepository 
 * @property  NegocioProdutoBarraRepository         $negocioProdutoBarraRepository 
 * @property  NotaFiscalProdutoBarraRepository      $notaFiscalProdutoBarraRepository 
 * @property  ProdutoHistoricoPrecoRepository       $produtoHistoricoPrecoRepository 
 */


class ProdutoController extends Controller
{
    public function __construct(
            ProdutoRepository $repository,
            ProdutoBarraRepository $produtoBarraRepository,
            ProdutoVariacaoRepository $produtoVariacaoRepository,
            ProdutoEmbalagemRepository $produtoEmbalagemRepository,
            NegocioProdutoBarraRepository $negocioProdutoBarraRepository,
            NotaFiscalProdutoBarraRepository $notaFiscalProdutoBarraRepository,
            ProdutoHistoricoPrecoRepository $produtoHistoricoPrecoRepository            
        ) {
        $this->repository                            = $repository;
        $this->produtoBarraRepository                = $produtoBarraRepository ;
        $this->produtoVariacaoRepository             = $produtoVariacaoRepository;
        $this->produtoEmbalagemRepository            = $produtoEmbalagemRepository; 
        $this->negocioProdutoBarraRepository         = $negocioProdutoBarraRepository; 
        $this->notaFiscalProdutoBarraRepository      = $notaFiscalProdutoBarraRepository; 
        $this->produtoHistoricoPrecoRepository       = $produtoHistoricoPrecoRepository;
                
        $this->bc = new Breadcrumb('Produto');
        $this->bc->addItem('Produto', url('produto'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        // Permissao
        $this->repository->authorize('listing');
        
        // Breadcrumb
        $this->bc->addItem('Listagem');
        
        // Filtro da listagem
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'filtros' => [
                    'inativo' => 1,
                ],
                'order' => [[
                    'column' => 3,
                    'dir' => 'ASC',
                ]],
            ];
        }
        
        
        // retorna View
        return view('produto.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }    
    
    /**
     * Monta json para alimentar a Datatable do index
     * 
     * @param  Request $request
     * @return  json
     */
    public function datatable(Request $request) {
        
        // Autorizacao
        $this->repository->authorize('listing');

        // Grava Filtro para montar o formulario da proxima vez que o index for carregado
        $this->setFiltro([
            'filtros' => $request['filtros'],
            'order' => $request['order'],
        ]);
        
        // Ordenacao
        $columns[0] = 'codproduto';
        $columns[1] = 'inativo';
        $columns[2] = 'codproduto';
        $columns[3] = 'produto';
        $columns[4] = 'codsubgrupoproduto';
        $columns[5] = 'codmarca';
        $columns[6] = 'codunidademedida';
        $columns[7] = 'referencia';
        $columns[8] = 'preco';

        $sort = [];
        if (!empty($request['order'])) {
            foreach ($request['order'] as $order) {
                $sort[] = [
                    'column' => $columns[$order['column']],
                    'dir' => $order['dir'],
                ];
            }
        }

        // Pega listagem dos registros
        $regs = $this->repository->listing($request['filtros'], $sort, $request['start'], $request['length']);
        
        // Monta Totais
        $recordsTotal = $regs['recordsTotal'];
        $recordsFiltered = $regs['recordsFiltered'];
        
        // Formata registros para exibir no data table
        $data = [];
        foreach ($regs['data'] as $reg) {
            $data[] = [
                url('produto', $reg->codproduto),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codproduto, 6),
                empty($reg->codprodutoimagem)?URL::asset('public/imagens/semimagem.jpg'):URL::asset("public/imagens/{$reg->ProdutoImagem->Imagem->arquivo}"),
                $reg->produto,
                [
                    'secaoproduto' => [
                        'codsecaoproduto' => $reg->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto,
                        'secaoproduto' => $reg->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
                        
                    ],
                    'familiaproduto' => [
                        'codfamiliaproduto' => $reg->SubGrupoProduto->GrupoProduto->codfamiliaproduto,
                        'familiaproduto' => $reg->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto
                    ],
                    'grupoproduto' => [
                        'codgrupoproduto' => $reg->SubGrupoProduto->codgrupoproduto,
                        'grupoproduto' => $reg->SubGrupoProduto->GrupoProduto->grupoproduto
                    ],
                    'subgrupoproduto' => [
                        'codsubgrupoproduto' => $reg->codsubgrupoproduto,
                        'subgrupoproduto' => $reg->SubGrupoProduto->subgrupoproduto,
                    ],
                    'marca' => [
                        'marca' => $reg->Marca->marca, 
                        'codmarca' => $reg->codmarca
                    ],
                    'referencia' => $reg->referencia
                ],
                $this->repository->listingPrecoEmbalagens($reg->ProdutoEmbalagemS()->orderBy(DB::raw('coalesce(quantidade, 0)'))->get(), $reg->preco, $reg->UnidadeMedida->sigla),
                $this->repository->listingProdutoVariacao($reg->ProdutoVariacaoS()->orderBy(DB::raw("coalesce(variacao, '')"), 'ASC')->get(), $reg->UnidadeMedida->sigla)
            ];
        }
        
        // Envelopa os dados no formato do data table
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorna o JSON
        return collect($ret);
    }    
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // cria um registro em branco
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        if ($request->get('duplicar')){
            $data = $this->repository->findOrFail($request->get('duplicar'));
            $this->repository->fill($data->getAttributes());
        } else {
            $this->repository->model->codtipoproduto = TipoProduto::MERCADORIA;
        }        
        
        // retorna view
        return view('produto.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // busca dados do formulario
        $data = $request->all();
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');        
        
        DB::beginTransaction();        
        try {        
            if (!$this->repository->create()) {
                throw new Exception('Erro ao Cria Produto!');
            }        
            
            $this->produtoVariacaoRepository->new([
                'codproduto' => $this->repository->model->codproduto
            ]);
            
            if (!$this->produtoVariacaoRepository->save()){
                throw new Exception('Erro ao Criar Variação!');
            }

            $this->produtoBarraRepository->new([
                'codproduto' => $this->repository->model->codproduto,
                'codprodutovariacao' => $this->produtoVariacaoRepository->model->codprodutovariacao
            ]);

            if (!$this->produtoBarraRepository->save()){
                throw new Exception ('Erro ao Criar Barras!');
            }

            DB::commit();

            Session::flash('flash_create', 'Produto criado!');
        } catch (Exception $ex) {
            DB::rollBack();
            Session::flash('flash_error', "Erro ao salvar registro! {$ex}");
        }
        
        // redireciona para o view
        return redirect("produto/{$this->repository->model->codproduto}");
    }    
        
    /**
     * Display the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // busca registro
        $this->repository->findOrFail($id);
        
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->produto);
        $this->bc->header = $this->repository->model->produto;
        
        // retorna show
        $estoque = null;
        switch ($request->get('_div'))
        {
            case 'div-imagens':
                $view = 'produto.show-imagens';
                break;
            case 'div-variacoes':
                $view = 'produto.show-variacoes';
                break;
            case 'div-embalagens':
                $view = 'produto.show-embalagens';
                break;
            case 'div-estoque':
                $estoque = $this->repository->getArraySaldoEstoque();
                $view = 'produto.show-estoque';
                break;
            case 'div-site':
                $view = 'produto.show-site';
                break;
            default:
                $view = 'produto.show';
        }
        
        $ret = view($view, ['bc' => $this->bc, 'model' => $this->repository->model, 'estoque' => $estoque]);
        
        return $ret;        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // busca regstro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->produto, url('produto', $this->repository->model->codproduto));
        $this->bc->header = $this->repository->model->produto;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('produto.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Busca registro para autorizar
        $this->repository->findOrFail($id);

        // Valida dados
        $data = $request->all();
        
        if(!isset($data['importado'])) {
            $data['importado'] = FALSE;
        }
        
        if(!isset($data['site'])) {
            $data['site'] = FALSE;
        }
        
        // autorizacao
        $this->repository->fill($data);
        $this->repository->authorize('update');

        DB::beginTransaction();
        
        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        try {
            $preco = $this->repository->model['original']['preco'];
            
            if (!$this->repository->save()){
                throw new Exception ('Erro ao alterar Produto!');
            }
            
            if($preco != $this->repository->model->preco) {
                $this->produtoHistoricoPrecoRepository->new([
                    'codproduto'  => $this->repository->model->codproduto,
                    'precoantigo' => $preco,
                    'preconovo'   => $this->repository->model->preco
                ]);
                
                if (!$this->produtoHistoricoPrecoRepository->create()){
                    throw new Exception ('Erro ao gravar Historico!');
                }
            }
            
            DB::commit();
            Session::flash('flash_update', 'Produto alterado!');
            return redirect("produto/{$this->repository->model->codproduto}"); 
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $this->repository->validator);
        }        

    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function site(Request $request, $id)
    {
        // busca regstro
        $this->repository->findOrFail($id);
        
        // Recupera dados form antigo caso erro de validacao
        if (!$data = $request->old()) {
            $data = [
                'site' => $this->repository->model->site,
                'metakeywordsite' => $this->repository->model->metakeywordsite,
                'metadescriptionsite' => $this->repository->model->metadescriptionsite,
                'codprodutoembalagem' => [
                    0 => 0,
                ],
                'vendesite' => [
                    0 => $this->repository->model->vendesite,
                ],
                'descricaosite' => [
                    0 => $this->repository->model->descricaosite,
                ],
                'peso' => [
                    0 => $this->repository->model->peso,
                ],
                'altura' => [
                    0 => $this->repository->model->altura,
                ],
                'largura' => [
                    0 => $this->repository->model->largura,
                ],
                'profundidade' => [
                    0 => $this->repository->model->profundidade,
                ],
            ];
            
            foreach ($this->repository->model->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe) {
                $data['codprodutoembalagem'][$pe->codprodutoembalagem] = $pe->codprodutoembalagem;
                $data['vendesite'][$pe->codprodutoembalagem] = $pe->vendesite;
                $data['descricaosite'][$pe->codprodutoembalagem] = $pe->descricaosite;
                $data['peso'][$pe->codprodutoembalagem] = $pe->peso;
                $data['altura'][$pe->codprodutoembalagem] = $pe->altura;
                $data['largura'][$pe->codprodutoembalagem] = $pe->largura;
                $data['profundidade'][$pe->codprodutoembalagem] = $pe->profundidade;
            }
        }
        $data['codproduto'] = $id;
        $data['unidademedida'][0] = $this->repository->model->UnidadeMedida->unidademedida;
        foreach ($this->repository->model->ProdutoEmbalagemS as $pe) {
            $data['unidademedida'][$pe->codprodutoembalagem] = $pe->UnidadeMedida->unidademedida;
            $data['quantidade'][$pe->codprodutoembalagem] = $pe->quantidade;
        }
        
        // autorizacao
        $this->repository->authorize('site');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->produto, url('produto', $this->repository->model->codproduto));
        $this->bc->header = $this->repository->model->produto;
        $this->bc->addItem('Site');
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('produto.site', ['bc'=>$this->bc, 'data'=>$data]);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function siteUpdate(Request $request, $id)
    {
        // Busca registro para autorizar
        $this->repository->findOrFail($id);

        // Valida dados
        $data = $request->all();
        
        // autorizacao
        $this->repository->authorize('site');

        DB::beginTransaction();
        
        if (!$this->repository->validateSite($data, $id)) {
            $this->data = $data;
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        $this->repository->fill([
            'vendesite' => $data['vendesite'][0]??false,
            'descricaosite' => $data['descricaosite'][0],
            'peso' => $data['peso'][0],
            'altura' => $data['altura'][0],
            'largura' => $data['largura'][0],
            'profundidade' => $data['profundidade'][0],
            'site' => $data['site']??false,
            'metakeywordsite' => $data['metakeywordsite'],
            'metadescriptionsite' => $data['metadescriptionsite'],
        ]);
        
        /*
        dd([
            'vendesite' => $data['vendesite'][0]??false,
            'descricaosite' => $data['descricaosite'][0],
            'peso' => $data['peso'][0],
            'altura' => $data['altura'][0],
            'largura' => $data['largura'][0],
            'profundidade' => $data['profundidade'][0],
            'site' => $data['site']??false,
            'metakeywordsite' => $data['metakeywordsite'],
            'metadescriptionsite' => $data['metadescriptionsite'],
        ]);
        */
        
        //DB::enableQueryLog();
        
        if (!$this->repository->save()){
            throw new Exception ('Erro ao alterar Produto!');
        }
        
        //$log = DB::getQueryLog();
        
        //dd($log);

        foreach ($data['codprodutoembalagem'] as $produtoembalagem) {
            
            if ($produtoembalagem == 0) {
                continue;
            }
            
            $this->produtoEmbalagemRepository->findOrFail($produtoembalagem);
            $this->produtoEmbalagemRepository->fill([
                'vendesite' => $data['vendesite'][$produtoembalagem]??false,
                'descricaosite' => $data['descricaosite'][$produtoembalagem],
                'peso' => $data['peso'][$produtoembalagem],
                'altura' => $data['altura'][$produtoembalagem],
                'largura' => $data['largura'][$produtoembalagem],
                'profundidade' => $data['profundidade'][$produtoembalagem],
            ]);
            
            if (!$this->produtoEmbalagemRepository->save()){
                throw new Exception ('Erro ao alterar Embalagem!');
            }
            
        }
        
        DB::commit();
        
        Session::flash('flash_update', 'Dados de integração com site alterado!');
        
        return redirect("produto/{$this->repository->model->codproduto}"); 
        
    }
    

    public function populaSecaoProduto(Request $request) {
        $this->repository->findOrFail($request->get('id'));
        $retorno = [
            'secaoproduto'      => $this->repository->model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto,
            'familiaproduto'    => $this->repository->model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codfamiliaproduto,
            'grupoproduto'      => $this->repository->model->SubGrupoProduto->GrupoProduto->codgrupoproduto,
            'subgrupoproduto'   => $this->repository->model->SubGrupoProduto->codsubgrupoproduto,
        ];
        
        return response()->json($retorno);
    }    
    
    public function buscaPorBarras(Request $request)
    {
        $barra = ProdutoBarra::buscaPorBarras($request->get('barras'));
        return response()->json($barra);
    }

    public function select2(Request $request)
    {
        // Parametros que o Selec2 envia
        $params = $request->get('params');
        
        // Quantidade de registros por pagina
        $registros_por_pagina = 100;
        
        // Se veio termo de busca
        if(!empty($params['term'])) {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = $this->repository->model->query();
            $qry->join('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto')
                ->join('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto')
                ->join('tblfamiliaproduto','tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto')
                ->join('tblsecaoproduto', 'tblsecaoproduto.codsecaoproduto', '=', 'tblfamiliaproduto.codsecaoproduto')
                ->join('tblmarca', 'tblmarca.codmarca', '=', 'tblproduto.codmarca');
                
            // Condicoes de busca
            if (strlen($params['term']) == 6 & is_numeric($params['term'])) {
                $qry->where('codproduto', '=', $params['term']);
            }
            else {
                foreach (explode(' ', $params['term']) as $palavra) {
                    $qry->whereRaw("(tblproduto.produto ilike '%{$palavra}%')");
                }
            }            
            
            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codproduto', 'produto', 'preco', 'referencia', 'tblproduto.inativo', 'tblsecaoproduto.secaoproduto', 'tblfamiliaproduto.familiaproduto', 'tblgrupoproduto.grupoproduto', 'tblsubgrupoproduto.subgrupoproduto', 'tblmarca.marca');
            $qry->orderBy('produto', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id'        =>  $item->codproduto,
                    'codigo'    => formataCodigo($item->codproduto, 6),
                    'produto'   => $item->produto,
                    'preco'     => formataNumero($item->preco),
                    'referencia'=> $item->referencia,
                    'inativo'   => $item->inativo,
                    'secaoproduto'     => $item->secaoproduto,
                    'familiaproduto'   => $item->familiaproduto,
                    'grupoproduto'     => $item->grupoproduto,
                    'subgrupoproduto'  => $item->subgrupoproduto,
                    'marca'     => $item->marca
                ];
            }
            
            // Monta Retorno
            return [
                'results' => $results,
                'params' => $params,
                'pagination' =>  [
                    'more' => ($total > $params['page'] * $registros_por_pagina)?true:false,
                ]
            ];

        } elseif($request->get('id')) {
            
            // Monta Retorno
            $this->repository->findOrFail($request->get('id'));            
            return [
                'id'            => $this->repository->model->codproduto,
                'produto'       => $this->repository->model->produto,
                'referencia'    => $this->repository->model->referencia,
                'preco'         => $this->repository->model->preco,
                'inativo'       => formataData($this->repository->model->inativo, 'C')
            ];
        }
    }
    

    public function typeahead(Request $request) 
    {
        $sql = $this->repository->model->query();
        $sql->palavras('produto', $request->get('q'))
            ->select('produto', 'codproduto');
            
        if($request->get('codsubgrupoproduto') != 'null') {
            $sql->where('codsubgrupoproduto', $request->get('codsubgrupoproduto'));
        }
            
        $sql->where('codproduto', '!=',  ($request->get('codproduto')?$request->get('codproduto'):0))
            ->orderBy('produto', 'DESC')
            ->limit(15);
        
        $query = $sql->get();
        
        $resultado = [];
        foreach ($query as $key => $value) {
            $resultado[] = [
                'produto' => $value['produto'],
                'codproduto' => $value['codproduto']
                ];
        }

        return  response()->json($resultado);
    }

    
    public function estoqueSaldo(Request $request) 
    {
        $query = DB::table('tblestoquesaldo')
            ->join('tblestoquelocalprodutovariacao', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao', '=', 'tblestoquesaldo.codestoquelocalprodutovariacao')
            ->where('codproduto', '=', $request->get('codproduto'))
            ->select('customedio', 'saldovalor', 'saldoquantidade');

        if($request->get('codestoquelocal')) $query->where('tblestoquelocalprodutovariacao.codestoquelocal', '=', $request->get('codestoquelocal'));
        if($request->get('fiscal') == 1) 
            $query->where('fiscal', '=', true);
        else 
            $query->where('fiscal', '=', false);
        $resultado = $query->get();

        return response()->json($resultado);
    }
    
    public function transferirVariacao(Request $request, $id)
    {
        // busca regstro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->produto, url('produto', $this->repository->model->codproduto));
        $this->bc->header = $this->repository->model->produto;
        $this->bc->addItem('Transferir Variação');
        
        return view('produto.transferir-variacao', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }
        
    public function transferirVariacaoSalvar(Request $request, $id)
    {
        $form = $request->all();
        
        $validator = Validator::make(
            $form, 
            [
                'codproduto'           => "required",
                'codprodutovariacao'   => 'required',
            ],
            [
                'codproduto.required'           => 'Selecione o produto de destino!',
                'codprodutovariacao.required'   => 'Selecione uma variação!',
            ]
        );
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        
        DB::BeginTransaction();
        
        foreach($form['codprodutovariacao'] as $codprodutovariacao) {

            $pv = $this->produtoVariacaoRepository->findOrFail($codprodutovariacao);
            $pv->codproduto = $form['codproduto'];
            $pv->save();
            
            foreach($pv->ProdutoBarraS as $pb) {

                $pb->codproduto = $form['codproduto'];

                if (!empty($pb->codprodutoembalagem)) {
                    $pe = $this->produtoEmbalagemRepository->model->where([
                        'codproduto' => $form['codproduto'],
                        'quantidade' => $pb->ProdutoEmbalagem->quantidade,
                    ])->first();
                    
                    if (!$pe) {
                        $pe = new ProdutoEmbalagem;
                        $pe->codproduto = $form['codproduto'];
                        $pe->quantidade = $pb->ProdutoEmbalagem->quantidade;
                        $pe->codunidademedida = $pb->ProdutoEmbalagem->codunidademedida;
                        $pe->preco = $pb->ProdutoEmbalagem->preco;
                        $pe->save();
                    }

                    $pb->codprodutoembalagem = $pe->codprodutoembalagem;
                }

                $pb->save();
            }
        }
        //DB::rollback();
        DB::commit();

        return redirect("produto/{$form['codproduto']}");
    }
    
    public function unificarBarras(Request $request, $id)
    {
        // busca registro
        $this->repository->findOrFail($id);
        
        //autorizacao
        $this->repository->authorize('unificarBarras');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->produto, url('produto', $this->repository->model->codproduto));
        $this->bc->addItem('Unificar Códigos de Barra');
        $this->bc->header = $this->repository->model->produto;
        
        $produto = $this->repository->detalhes();
        
        dd($produto->variacao);
        
        dd('aqui');
    }
    
    public function unificarBarrasSalvar(Request $request, $id)
    {
        dd('aqui');
    }


    public function sincronizaProdutoOpenCart(Request $request)
    {
        try {
            IntegracaoOpenCart::sincronizaProdutos($request->get('id'));
            $retorno = ['resultado' => true, 'mensagem' => 'Produto sincronizado com sucesso!'];
        } catch (\Exception $e) {
            $retorno = ['resultado' => false, 'mensagem' => 'Erro ao sincronizar produto!', 'exception' => "$e"];
        }
        return response()->json($retorno);
    }
    
    public function consulta(Request $request, $barras) 
    {
        //dd($barras);
        //dd($this->produtoBarraRepository->buscaPorBarras($barras));
        if (!$barras = $this->produtoBarraRepository->buscaPorBarras($barras)) {
        //if (!$barras = ProdutoBarra::buscaPorBarras($barras)) {
            return [
                'resultado' => false, 
                'mensagem' => 'Nenhum produto localizado!',
            ];
        }
        
        // Imagens
        $imagens = [];
        
        foreach ($barras->Produto->ImagemS as $imagem) {
            $imagens[] = [
                'codimagem' => $imagem->codimagem,
                'url' => URL::asset('public/imagens/'.$imagem->observacoes),
            ];
        }
        if (sizeof($imagens) == 0) {
            $imagens[] = [
                'codimagem' => null,
                'url' => URL::asset('public/imagens/semimagem.jpg'),
            ];
        }
        
        // Variacoes
        $variacoes = [];
        $estoquelocais = [];
        foreach ($barras->Produto->ProdutoVariacaoS()->orderByRaw("variacao asc nulls first")->get() as $pv) {
            $produtobarras = [];
            foreach ($pv->ProdutoBarraS as $pb) {
                $produtobarras[] = [
                    'codprodutobarra' => $pb->codprodutobarra,
                    'codprodutoembalagem' => $pb->codprodutoembalagem,
                    'barras' => $pb->barras,
                    'detalhes' => $pb->variacao,
                    'referencia' => $pb->referencia,
                    'unidademedida' => $pb->UnidadeMedida->sigla,
                    'quantidade' => (!empty($pb->codprodutoembalagem)?(float)$pb->ProdutoEmbalagem->quantidade:null),
                ];
            }
            $saldos = [];
            $saldo = 0;
            foreach ($pv->EstoqueLocalProdutoVariacaoS()->orderBy('codestoquelocal')->get() as $elpv) {
                foreach ($elpv->EstoqueSaldoS()->where('fiscal', false)->get() as $es) {
                    $saldo += (float)$es->saldoquantidade;
                    $estoquelocais[$elpv->codestoquelocal] = [
                        'codestoquelocal' => $elpv->codestoquelocal,
                        'estoquelocal' => $elpv->EstoqueLocal->estoquelocal,
                    ];
                    $saldos[$elpv->codestoquelocal] = [
                        'codestoquesaldo' => $es->codestoquesaldo,
                        'url' => url("estoque-saldo/{$es->codestoquesaldo}"),
                        'codestoquelocal' => $elpv->codestoquelocal,
                        'saldoquantidade' => (float)$es->saldoquantidade,
                        'saldovalor' => (float)$es->saldovalor,
                    ];
                }
            }
            $variacoes[] = [
                'codprodutovariacao' => $pv->codprodutovariacao,
                'referencia' => $pv->referencia,
                'marca' => (!empty($pv->codmarca)?$pv->Marca->marca:null),
                'variacao' => $pv->variacao,
                'barras' => $produtobarras,
                'saldo' => $saldo,
                'saldos' => $saldos,
            ];
        }
        
        // Embalagens
        $embalagens[] = [
            'codprodutoembalagem' => null,
            'quantidade' => null,
            'unidademedida' => $barras->Produto->UnidadeMedida->unidademedida,
            'preco' => (float)$barras->Produto->preco,
            'precocalculado' => false,
        ];
        foreach ($barras->Produto->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $embalagem) {
            $embalagens[] = [
                'codprodutoembalagem' => $embalagem->codprodutoembalagem,
                'quantidade' => (float)$embalagem->quantidade,
                'unidademedida' => $embalagem->UnidadeMedida->unidademedida,
                'preco' => (float)(!empty($embalagem->preco)?$embalagem->preco:$embalagem->quantidade * $barras->Produto->preco),
                'precocalculado' => empty($embalagem->preco),
            ];
        }
        
        //dd($barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codimagem);
        //dd($barras->Produto->SubGrupoProduto->codimagem);
        
        $produto = [
            'codproduto' => $barras->codproduto,
            'url' => url("produto/{$barras->codproduto}"),
            'codprodutobarra' => $barras->codprodutobarra,
            'barras' => $barras->barras,
            'produto' => $barras->descricao,
            'inativo' => $barras->Produto->inativo,
            'unidademedida' => $barras->UnidadeMedida->unidademedida,
            'slglaunidademedida' => $barras->UnidadeMedida->sigla,
            'referencia' => $barras->referencia,
            'marca' => [
                'codmarca' => $barras->Marca->codmarca,
                'marca' => $barras->Marca->marca,
                'url' => url("marca/{$barras->Marca->codmarca}"),
                'urlimagem' => (!empty($barras->Marca->codimagem)?URL::asset('public/imagens/'.$barras->Marca->Imagem->observacoes):null),
            ],
            'subgrupoproduto' => [
                'codsubgrupoproduto' => $barras->Produto->codsubgrupoproduto,
                'subgrupoproduto' => $barras->Produto->SubGrupoProduto->subgrupoproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->Imagem->observacoes):null),
                'url' => url("sub-grupo-produto/{$barras->Produto->codsubgrupoproduto}"),
            ],
            'grupoproduto' => [
                'codgrupoproduto' => $barras->Produto->SubGrupoProduto->codgrupoproduto,
                'grupoproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->grupoproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->GrupoProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->GrupoProduto->Imagem->observacoes):null),
                'url' => url("grupo-produto/{$barras->Produto->SubGrupoProduto->codgrupoproduto}"),
            ],
            'familiaproduto' => [
                'codfamiliaproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto,
                'familiaproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->Imagem->observacoes):null),
                'url' => url("familia-produto/{$barras->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto}"),
            ],
            'secaoproduto' => [
                'codsecaoproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto,
                'secaoproduto' => $barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto,
                'urlimagem' => (!empty($barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codimagem)?URL::asset('public/imagens/'.$barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->Imagem->observacoes):null),
                'url' => url("secao-produto/{$barras->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto}"),
            ],
            'preco' => $barras->preco,
            'imagens' => $imagens,
            'variacoes' => $variacoes,
            'embalagens' => $embalagens,
            'estoquelocais' => $estoquelocais,
        ];
        
        
        return [
            'resultado' => true,
            'produto' => $produto,
        ];
        //dd($prod);
    }
    
    public function quiosque (Request $request)
    {
        return view('produto.quiosque-legado');
    }
    
    public function alterarImagemPadrao(Request $request, $id) 
    {
        // busca registro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // altera imagem
        if (!$this->repository->alterarImagemPadrao($request->codimagem, $request->codprodutoembalagem, $request->codprodutovariacao)) {
            abort (500, 'Erro ao alterar imagem padrão!');
        }
        
        //retorna sucesso
        return ['OK' => true];
    }
    
    public function alterarImagemOrdem(Request $request, $id) 
    {
        // busca registro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // altera imagem
        if (!$this->repository->alterarImagemOrdem($request->codimagem)) {
            abort (500, 'Erro ao alterar ordem das imagens!');
        }
        
        //retorna sucesso
        return ['OK' => true];
    }
    
}
