<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\PranchetaRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  PranchetaRepository $repository 
 */
class PranchetaController extends Controller
{

    public function __construct(PranchetaRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Categorias de Prancheta');
        $this->bc->addItem('Categorias de Prancheta', url('prancheta'));
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
        return view('prancheta.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codprancheta';
        $columns[1] = 'inativo';
        $columns[2] = 'codprancheta';
        $columns[3] = 'prancheta';
        $columns[4] = 'observacoes';

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
                url('prancheta', $reg->codprancheta),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codprancheta),
                $reg->prancheta,
                $reg->observacoes,
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
    public function create()
    {
        // cria um registro em branco
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('prancheta.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parent::store($request);
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Prancheta criado!');
        
        // redireciona para o view
        return redirect("prancheta/{$this->repository->model->codprancheta}");
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
        $this->bc->addItem($this->repository->model->prancheta);
        $this->bc->header = $this->repository->model->prancheta;
        
        $itens = $this->repository->listagemProdutos(null, $request->codestoquelocal);
        
        if ($request->debug == 'true') {
            return $itens;
        }
        
        // retorna show
        $quiosque = (bool) $request->quiosque;
        return view('prancheta.show', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'itens'=>$itens, 'codestoquelocal' => $request->codestoquelocal, 'quiosque'=>$quiosque]);
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
        $this->bc->addItem($this->repository->model->prancheta, url('prancheta', $this->repository->model->codprancheta));
        $this->bc->header = $this->repository->model->prancheta;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('prancheta.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Prancheta alterado!');
        
        // redireciona para view
        return redirect("prancheta/{$this->repository->model->codprancheta}"); 
    }
    
    public function quiosque(Request $request, $codestoquelocal = null) 
    {
        $itens = $this->repository->listagemProdutos($codestoquelocal);
        
        if ($request->debug == 'true') {
            return $itens;
        }
        
        return view('prancheta.quiosque', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'itens'=>$itens, 'codestoquelocal' => $request->codestoquelocal]);
    }
    
    public function quiosqueProduto(Request $request, $codpranchetaproduto, $codestoquelocal = null) 
    {
        // busca registro
        $produto = $this->repository->detalhesProduto($codpranchetaproduto, $codestoquelocal);
        
        if ($request->debug == 'true') {
            return $produto;
        }
        
        // retorna show
        return view('prancheta.quiosque-produto', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'produto'=>$produto, 'codestoquelocal' => $codestoquelocal]);
    }
    
}
