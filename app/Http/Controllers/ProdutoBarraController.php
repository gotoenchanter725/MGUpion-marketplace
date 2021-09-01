<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ProdutoBarraRepository;
use MGLara\Repositories\ProdutoRepository;
use MGLara\Jobs\EstoqueGeraMovimentoProdutoVariacao;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;
use DB;
/**
 * @property  ProdutoBarraRepository $repository 
 * @property  ProdutoRepository $produtoRepository 
 */
class ProdutoBarraController extends Controller
{

    public function __construct(ProdutoBarraRepository $repository, ProdutoRepository $produtoRepository) {
        $this->repository = $repository;
        $this->produtoRepository = $produtoRepository;
        $this->bc = new Breadcrumb('Produto Barra');
        //$this->bc->addItem('Produto Barra', url('produto-barra'));
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
        return view('produto-barra.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codprodutobarra';
        $columns[1] = 'inativo';
        $columns[2] = 'codprodutobarra';
        $columns[3] = 'variacao';
        $columns[4] = 'codproduto';
        $columns[5] = 'barras';
        $columns[6] = 'referencia';
        $columns[7] = 'codmarca';
        $columns[8] = 'codprodutoembalagem';
        $columns[9] = 'codprodutovariacao';

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
                url('produto-barra', $reg->codprodutobarra),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codprodutobarra),
                $reg->variacao,
                $reg->codproduto,
                $reg->barras,
                $reg->referencia,
                $reg->codmarca,
                $reg->codprodutoembalagem,
                $reg->codprodutovariacao,
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
        $this->repository->fill($request->all());
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Produto', url('produto'));
        $this->bc->addItem($this->repository->model->Produto->produto, url('produto', $this->repository->model->codproduto));
        $this->bc->addItem('Novo Código de Barras');
        
        // retorna view
        return view('produto-barra.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        if ($this->repository->model->codprodutoembalagem == 0) {
            $this->repository->model->codprodutoembalagem = null;
        }
        
        // autoriza
        $this->repository->authorize('create');
        
        // cria
        if (!$this->repository->save()) {
            abort(500);
        }        
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Código de Barras criado!');
        
        // redireciona para o view
        return redirect("produto/{$this->repository->model->produto->codproduto}");
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
        $this->bc->addItem('Produto', url('produto'));
        $this->bc->addItem($this->repository->model->Produto->produto, url('produto', $this->repository->model->codproduto));
        $this->bc->addItem($this->repository->model->barras);
        $this->bc->header = $this->repository->model->barras;
        
        // retorna show
        return view('produto-barra.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->barras);        
        $this->bc->addItem('Alterar');
        $this->bc->header = $this->repository->model->barras;         
        
        
        // retorna formulario edit
        return view('produto-barra.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'produto'=>$this->repository->model->Produto]);
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
        // Busca registro para autorizar
        $this->repository->findOrFail($id);
        $codprodutovariacao_original = $this->repository->model->codprodutovariacao;
        
        // Valida dados
        $data = $request->all();
        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        // autorizacao
        $this->repository->fill($data);
        $this->repository->authorize('update');
        
        if ($this->repository->model->codprodutoembalagem == 0) {
            $this->repository->model->codprodutoembalagem = null;
        }        
        
        // salva
        if (!$this->repository->update()) {
            abort(500);
        }
        
        //Recalcula movimento de estoque caso trocou o codigo de barras de variacao
        if ($this->repository->model->codprodutovariacao != $codprodutovariacao_original) {
            $this->dispatch((new EstoqueGeraMovimentoProdutoVariacao($this->repository->model->codprodutovariacao))->onQueue('medium'));
            $this->dispatch((new EstoqueGeraMovimentoProdutoVariacao($codprodutovariacao_original))->onQueue('medium'));
        }
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Produto Barra alterado!');
        
        // redireciona para view
        return redirect("produto/{$this->repository->model->codproduto}"); 
    }
    
    public function select2(Request $request) 
    {
        // Parametros que o Selec2 envia
        $params = $request->get('params');
        
        // Quantidade de registros por pagina
        $registros_por_pagina = 100;
        
        if(!empty($request->get('id'))) {    
            // Monta Retorno
            $item = $this->repository->findOrFail($request->get('id'));
            return [
                'id'                => $item->codprodutobarra,
                'codprodutobarra'   => $item->codprodutobarra,
                'produto'           => $item->descricao,
                'barras'            => $item->barras,
                'referencia'        => $item->referencia,
                'preco'             => $item->preco,
            ];
            
        } else {        
                        // Numero da Pagina
            $params['page'] = $params['page']??1;

            $qry = DB::table('vwprodutobarra');
            
            $tokens = $params['term'];

            // Decide Ordem
            $ordem = (strstr($tokens, '$'))?
                    'vwprodutobarra.preco ASC, vwprodutobarra.produto ASC, vwprodutobarra.quantidade ASC nulls first, vwprodutobarra.descricao asc':
                    'vwprodutobarra.produto ASC, vwprodutobarra.quantidade ASC nulls first, vwprodutobarra.descricao asc';

            // Limpa string
            $tokens = str_replace('$', ' ', $tokens);
            $tokens = trim(preg_replace('/(\s\s+|\t|\n)/', ' ', $tokens));
            $tokens = explode(' ', $tokens);

            // Percorre todas strings
            foreach ($tokens as $str) {
                $qry->where(function ($q2) use ($str) {

                    $q2->where('descricao', 'ILIKE', "%$str%");

                    if ($str == formataNumero((float) str_replace(',', '.', $str), 2)) {
                        $q2->orWhere('preco', '=', (float) str_replace(',', '.', $str));
                    } else {
                        if (strlen($str) == 6 & is_numeric($str)) {
                            $q2->orWhere('codproduto', '=', $str);
                        }
                        if (is_numeric($str)) {
                            $q2->orWhere('barras', 'ilike', "%$str%");
                        }
                    }
                });
            }

            if ($request->get('somenteAtivos') == 'true') {
                $qry->whereNull('inativo');
            }
            
            // Total de registros
            $total = $qry->count();
            
            $qry->select('codprodutobarra', 'descricao', 'sigla', 'codproduto', 'barras', 'preco', 'referencia', 'inativo', 'secaoproduto', 'familiaproduto', 'grupoproduto', 'subgrupoproduto', 'marca');
            $qry->orderByRaw($ordem);
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id'               => $item->codprodutobarra,
                    'barras'           => $item->barras,
                    'codproduto'       => formataCodigo($item->codproduto, 6),
                    'produto'          => $item->descricao,
                    'preco'            => formataNumero($item->preco),
                    'referencia'       => $item->referencia,
                    'inativo'          => $item->inativo,
                    'secaoproduto'     => $item->secaoproduto,
                    'familiaproduto'   => $item->familiaproduto,
                    'grupoproduto'     => $item->grupoproduto,
                    'subgrupoproduto'  => $item->subgrupoproduto,
                    'marca'            => $item->marca,
                    'unidademedida'    => $item->sigla,
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
