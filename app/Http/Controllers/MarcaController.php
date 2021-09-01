<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;
use MGLara\Repositories\MarcaRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

class MarcaController extends Controller
{
    public function __construct(MarcaRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Marcas');
        $this->bc->addItem('Marcas', url('marca'));
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
        return view('marca.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codmarca';
        $columns[1] = 'inativo';
        $columns[2] = 'codmarca';
        $columns[3] = 'marca';
        $columns[4] = 'criacao';
        $columns[5] = 'alteracao';
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
                url('marca', $reg->codmarca),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codmarca),
                $reg->marca,
                formataData($reg->criacao, 'C'),
                formataData($reg->alteracao, 'C'),
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
        return view('marca.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Marca criada!');
        
        // redireciona para o view
        return redirect("marca/{$this->repository->model->codmarca}");
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
        $this->bc->addItem($this->repository->model->marca);
        $this->bc->header = $this->repository->model->marca;
        
        // retorna show
        return view('marca.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->marca, url('marca', $this->repository->model->codmarca));
        $this->bc->header = $this->repository->model->marca;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('marca.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        return redirect("marca/{$this->repository->model->codmarca}"); 
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
            foreach (explode(' ', $params['term']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->whereRaw("(tblmarca.marca ilike '%{$palavra}%')");
                }
            }
            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codmarca', 'marca', 'inativo');
            $qry->orderBy('marca', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codmarca,
                    'marca' => $item->marca,
                    'inativo' => formataData($item->inativo, 'C')
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
                'id' => $item->codmarca,
                'marca' => $item->marca,
                'inativo' => formataData($item->inativo, 'C')
            ];
        }
    }

    
    public function buscaCodproduto($id)
    {
        $model = Marca::findOrFail($id);
        foreach ($model->ProdutoS as $prod)
            $arr_codproduto[] = $prod->codproduto;
        echo json_encode($arr_codproduto);        
    }
}
