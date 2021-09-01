<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;
use MGLara\Repositories\SecaoProdutoRepository;
use MGLara\Repositories\FamiliaProdutoRepository;
use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

class SecaoProdutoController extends Controller
{
    public function __construct(SecaoProdutoRepository $repository, FamiliaProdutoRepository $familiaProdutoRepository) {
        $this->repository = $repository;
        $this->familiaProdutoRepository = $familiaProdutoRepository;
        $this->bc = new Breadcrumb('Seções');
        $this->bc->addItem('Seções', url('secao-produto'));
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
                    'dir' => 'ASC'
                ]],
            ];
        }
        
        // retorna View
        return view('secao-produto.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }

    /**
     * Monta json para alimentar a Datatable do index
     * 
     * @param Request $request
     * @return json
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
        $columns[0] = 'codsecaoproduto';
        $columns[1] = 'inativo';
        $columns[2] = 'codsecaoproduto';
        $columns[3] = 'secaoproduto';
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
                url('secao-produto', $reg->codsecaoproduto),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codsecaoproduto),
                $reg->secaoproduto,
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
    public function create()
    {
        // cria um registro em branco
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Nova');
        
        // retorna view
        return view('secao-produto.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parent::store($request);
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Seções de Produto criada!');
        
        // redireciona para o view
        return redirect("secao-produto/{$this->repository->model->codsecaoproduto}");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // busca registro
        $this->repository->findOrFail($id);
        
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->secaoproduto);
        $this->bc->header = $this->repository->model->secaoproduto;
        
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'filtros' => [
                    'codsecaoproduto' => $this->repository->model->codsecaoproduto,
                    'inativo' => 1,
                ],
                'order' => [[
                    'column' => 3, 
                    'dir' => 'ASC'
                ]],
            ];
        }

        // retorna show
        return view('secao-produto.show', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'filtro'=>$filtro]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // busca regstro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->secaoproduto, url('secao-produto', $this->repository->model->codsecaoproduto));
        $this->bc->header = $this->repository->model->secaoproduto;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('secao-produto.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        
        parent::update($request, $id);
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Registro alterado!');
        
        // redireciona para view
        return redirect("secao-produto/{$this->repository->model->codsecaoproduto}"); 
    }
}
