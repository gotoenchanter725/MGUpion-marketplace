<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\CestRepository;
use MGLara\Repositories\NcmRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  CestRepository $repository 
 * @property  NcmRepository $ncmRepository 
 */
class CestController extends Controller
{
    public function __construct(CestRepository $repository, NcmRepository $ncmRepository) {
        $this->repository = $repository;
        $this->ncmRepository = $ncmRepository;
        $this->bc = new Breadcrumb('Cest');
        $this->bc->addItem('Cest', url('cest'));
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
        return view('cest.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codcest';
        $columns[1] = 'inativo';
        $columns[2] = 'codcest';
        $columns[3] = 'codcest';
        $columns[4] = 'cest';
        $columns[5] = 'ncm';
        $columns[6] = 'descricao';
        $columns[7] = 'codncm';

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
                url('cest', $reg->codcest),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codcest),
                $reg->codcest,
                $reg->cest,
                $reg->ncm,
                $reg->descricao,
                $reg->codncm,
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
        return view('cest.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Cest criado!');
        
        // redireciona para o view
        return redirect("cest/{$this->repository->model->codcest}");
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
        $this->bc->addItem($this->repository->model->codcest);
        $this->bc->header = $this->repository->model->codcest;
        
        // retorna show
        return view('cest.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codcest, url('cest', $this->repository->model->codcest));
        $this->bc->header = $this->repository->model->codcest;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('cest.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Cest alterado!');
        
        // redireciona para view
        return redirect("cest/{$this->repository->model->codcest}"); 
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
                'id'        => $item->codcest,
                'ncm'       => formataNcm($item->Ncm->ncm),
                'cest'      => formataNcm($item->cest),
                'descricao' => $item->descricao
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $ncm = $this->ncmRepository->findOrFail($request->get('codncm'));
            $cests = $ncm->cestsDisponiveis();            
            $results = [];
            foreach($cests as $cest)
            {
                $results[] = array(
                    'id'        => $cest['codcest'],
                    'ncm'       => formataNcm($cest['ncm']),
                    'cest'      => formataCest($cest['cest']),
                    'descricao' => $cest['descricao'],
                );
            }  
            
            // Total de registros
            $total = count($cests);
            
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
