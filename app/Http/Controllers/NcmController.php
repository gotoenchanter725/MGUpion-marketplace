<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\NcmRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

class NcmController extends Controller
{
    public function __construct(NcmRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('NCM');
        $this->bc->addItem('NCM', url('ncm'));
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
        $regs = $this->repository->model->whereNull('codncmpai')->get();
        $ncms = $this->repository->model->find($request->get('ncmpai'));
        
        // retorna View
        return view('ncm.index', ['bc'=>$this->bc, 'model'=>$regs, 'ncms'=>$ncms]);
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
        return view('ncm.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Ncm criado!');
        
        // redireciona para o view
        return redirect("ncm/{$this->repository->model->codncm}");
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
        $this->bc->addItem($this->repository->model->ncm);
        $this->bc->header = $this->repository->model->ncm;
        
        // retorna show
        return view('ncm.show', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'filhos'=>$this->repository->model->NcmS()->get()]);
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
        $this->bc->addItem($this->repository->model->codncm, url('ncm', $this->repository->model->codncm));
        $this->bc->header = $this->repository->model->codncm;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('ncm.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Ncm alterado!');
        
        // redireciona para view
        return redirect("ncm/{$this->repository->model->codncm}"); 
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
            
            // Condicoes de busca
            $numero = numeroLimpo(trim($params['term']));
            $qry->where('descricao', 'ILIKE', "%{$params['term']}%");

            if (!empty($numero)) {
                $qry->orWhere('ncm', 'ILIKE', "%$numero%");
            }
            
            
            //if ($request->get('somenteAtivos') == 'true') {
            //    $qry->ativo();
            //}
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codncm', 'ncm', 'descricao');
            $qry->orderBy('ncm', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codncm,
                    'ncm' => formataNcm($item->ncm),
                    'descricao' => $item->descricao,
                    //'inativo' => formataData($item->inativo, 'C'),
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
            $item = $this->repository->findOrFail($request->get('id'));
            return [
                'id' => $item->codncm,
                'ncm' => formataNcm($item->ncm),
                'descricao' => $item->descricao,
                //'inativo' => formataData($item->inativo, 'C'),
            ];
        }
    }    
}
