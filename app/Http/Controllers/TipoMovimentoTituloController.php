<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\TipoMovimentoTituloRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  TipoMovimentoTituloRepository $repository 
 */
class TipoMovimentoTituloController extends Controller
{

    public function __construct(TipoMovimentoTituloRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Tipo Movimento Titulo');
        $this->bc->addItem('Tipo Movimento Titulo', url('tipo-movimento-titulo'));
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
            $filtro['filtros'] = [
                'inativo' => 1,
            ];
            $filtro['order'] = [
                ['column' => 3, 'dir' => 'ASC']
            ];              
        }
        
        
        // retorna View
        return view('tipo-movimento-titulo.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codtipomovimentotitulo';
        $columns[1] = 'inativo';
        $columns[2] = 'codtipomovimentotitulo';
        $columns[3] = 'tipomovimentotitulo';
        $columns[4] = 'implantacao';
        $columns[5] = 'ajuste';
        $columns[6] = 'armotizacao';
        $columns[7] = 'juros';
        $columns[8] = 'desconto';
        $columns[9] = 'pagamento';
        $columns[10] = 'estorno';
        $columns[11] = 'observacao';

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
                url('tipo-movimento-titulo', $reg->codtipomovimentotitulo),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codtipomovimentotitulo),
                $reg->tipomovimentotitulo,
                $reg->implantacao,
                $reg->ajuste,
                $reg->armotizacao,
                $reg->juros,
                $reg->desconto,
                $reg->pagamento,
                $reg->estorno,
                $reg->observacao,
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
        return view('tipo-movimento-titulo.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Tipo Movimento Titulo criado!');
        
        // redireciona para o view
        return redirect("tipo-movimento-titulo/{$this->repository->model->codtipomovimentotitulo}");
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
        $this->bc->addItem($this->repository->model->tipomovimentotitulo);
        $this->bc->header = $this->repository->model->tipomovimentotitulo;
        
        // retorna show
        return view('tipo-movimento-titulo.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->tipomovimentotitulo, url('tipo-movimento-titulo', $this->repository->model->codtipomovimentotitulo));
        $this->bc->header = $this->repository->model->tipomovimentotitulo;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('tipo-movimento-titulo.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Tipo Movimento Titulo alterado!');
        
        // redireciona para view
        return redirect("tipo-movimento-titulo/{$this->repository->model->codtipomovimentotitulo}"); 
    }
    
}
