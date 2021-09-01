<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ProdutoVariacaoRepository;
use MGLara\Repositories\ProdutoRepository;
use MGLara\Repositories\ProdutoBarraRepository;

use MGLara\Models\ProdutoVariacao;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Illuminate\Support\Facades\DB;

/**
 * @property  ProdutoVariacaoRepository $repository 
 * @property  ProdutoBarraRepository $produtoBarraRepository 
 * @property  ProdutoRepository $produtoRepository 
 */
class ProdutoVariacaoController extends Controller
{

    public function __construct(ProdutoVariacaoRepository $repository, ProdutoRepository $produtoRepository, ProdutoBarraRepository $produtoBarraRepository) {
        $this->repository = $repository;
        $this->produtoRepository = $produtoRepository;
        $this->produtoBarraRepository = $produtoBarraRepository;
        $this->bc = new Breadcrumb('Produto');
        //$this->bc->addItem('Produto');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
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
                    'column' => 0,
                    'dir' => 'DESC',
                ]],
            ];
        }
        
        
        // retorna View
        return view('produto-variacao.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codprodutovariacao';
        $columns[1] = 'inativo';
        $columns[2] = 'codprodutovariacao';
        $columns[3] = 'codprodutovariacao';
        $columns[4] = 'codproduto';
        $columns[5] = 'variacao';
        $columns[6] = 'referencia';
        $columns[7] = 'codmarca';
        $columns[8] = 'codopencart';
        $columns[9] = 'dataultimacompra';
        $columns[10] = 'custoultimacompra';
        $columns[11] = 'quantidadeultimacompra';

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
                url('produto-variacao', $reg->codprodutovariacao),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codprodutovariacao),
                $reg->codprodutovariacao,
                $reg->codproduto,
                $reg->variacao,
                $reg->referencia,
                $reg->codmarca,
                $reg->codopencart,
                $reg->dataultimacompra,
                $reg->custoultimacompra,
                $reg->quantidadeultimacompra,
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
     * @return  \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // cria um registro em branco
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // instancia produto
        $this->produtoRepository->findOrFail($request->get('codproduto'));
        
        // breadcrumb
        $this->bc->addItem('Produto', url('produto'));
        $this->bc->addItem($this->produtoRepository->model->produto, url('produto', $this->produtoRepository->model->codproduto));
        $this->bc->addItem('Nova Variação');
        $this->bc->header = 'Nova Variação';
        
        // retorna view
        return view('produto-variacao.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        
        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        DB::beginTransaction();
        
        try {
            if(!$this->repository->save()){
                throw new Exception ('Erro ao Criar Variação!');
            }
            
            $this->produtoBarraRepository->new([
                'codproduto' => $this->repository->model->codproduto,
                'codprodutovariacao' =>  $this->repository->model->codprodutovariacao
            ]);
            
            if(!$this->produtoBarraRepository->save()){
                throw new \Exception ('Erro ao Criar Barras!');
            }
            
            $i = 0;

            foreach ($this->repository->model->Produto->ProdutoEmbalagemS as $pe)
            {
                $this->produtoBarraRepository->new([
                    'codproduto' => $this->repository->model->codproduto,
                    'codprodutovariacao' =>  $this->repository->model->codprodutovariacao,
                    'codprodutoembalagem' => $pe->codprodutoembalagem
                ]);                

                if (!$this->produtoBarraRepository->save()){
                    throw new Exception ("Erro ao Criar Barras da embalagem {$pe->descricao}!");
                }
                $i++;
            }

            DB::commit();
            
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $this->repository->validator);
        }        

        // Mensagem de registro criado
        Session::flash('flash_create', 'Produto Variacao criado!');
        
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
        $this->bc->addItem($this->repository->model->codprodutovariacao);
        $this->bc->header = $this->repository->model->codprodutovariacao;
        
        // retorna show
        return view('produto-variacao.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem('Produto', url('produto'));
        $this->bc->addItem($this->repository->model->produto->produto, url('produto', $this->repository->model->codproduto));
        $this->bc->addItem($this->repository->model->variacao);        
        $this->bc->addItem('Alterar');
        $this->bc->header = $this->repository->model->variacao; 
        
        // retorna formulario edit
        return view('produto-variacao.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        parent::update($request, $id);
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Produto Variacao alterado!');
        
        // redireciona para view
        return redirect("produto/{$this->repository->model->codproduto}");
    }

    public function select2(Request $request)
    {
        // Parametros que o Selec2 envia
        $params = $request->get('params');
        
        // Quantidade de registros por pagina
        $registros_por_pagina = 100;
        
        if($request->get('id')) {
            
            // Monta Retorno
            $item = ProdutoVariacao::findOrFail($request->get('id'));
            return [
                'id' => $item->codprodutovariacao,
                'variacao' => empty($item->variacao)?'{ Sem Variacao }':$item->variacao
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = ProdutoVariacao::where('codproduto', '=', $request->codproduto);

            foreach (explode(' ', trim($request->get('q'))) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('variacao', 'ilike', "%$palavra%");
                }
            }



            //if ($request->get('somenteAtivos') == 'true') {
            //    $qry->ativo();
            //}
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('variacao', 'codprodutovariacao');
            $qry->orderBy('variacao', 'ASC')->orderByRaw('variacao nulls first');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codprodutovariacao,
                    'variacao' => empty($item->variacao)?'{ Sem Variacao }':$item->variacao
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
        }
    }
    
}
