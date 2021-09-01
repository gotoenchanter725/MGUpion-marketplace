<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

use MGLara\Models\FamiliaProduto;
use MGLara\Repositories\GrupoProdutoRepository;
use MGLara\Models\SubGrupoProduto;
use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

class GrupoProdutoController extends Controller
{
    public function __construct(GrupoProdutoRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Seções');
        $this->bc->addItem('Seções', url('secao-produto'));
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
        $columns[0] = 'codgrupoproduto';
        $columns[1] = 'inativo';
        $columns[2] = 'codgrupoproduto';
        $columns[3] = 'grupoproduto';
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
                url('grupo-produto', $reg->codgrupoproduto),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codgrupoproduto),
                $reg->grupoproduto,
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
        return view('grupo-produto.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        parent::store($request);
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Seções de Produto criada!');
        
        // redireciona para o view
        return redirect("grupo-produto/{$this->repository->model->codgrupoproduto}");
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
        $this->bc->addItem($this->repository->model->FamiliaProduto->SecaoProduto->secaoproduto, url('secao-produto', $this->repository->model->codsecaoproduto));
        $this->bc->addItem($this->repository->model->FamiliaProduto->familiaproduto, url('familia-produto', $this->repository->model->FamiliaProduto->codfamiliaproduto));
        $this->bc->addItem($this->repository->model->grupoproduto);
        $this->bc->header = $this->repository->model->grupoproduto;
        
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

        // retorna show
        return view('grupo-produto.show', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'filtro'=>$filtro]);
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
        $this->bc->addItem($this->repository->model->grupoproduto, url('grupo-produto', $this->repository->model->codsecaoproduto));
        $this->bc->header = $this->repository->model->grupoproduto;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('grupo-produto.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        return redirect("grupo-produto/{$this->repository->model->codgrupoproduto}"); 
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
                'id' => $item->codgrupoproduto,
                'grupoproduto' => $item->grupoproduto,
                'inativo' => $item->inativo,
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = $this->repository->model->where('codfamiliaproduto', $request->codfamiliaproduto);
            
            if(!empty($params['term'])) {
                foreach (explode(' ', $params['term']) as $palavra) {
                    if (!empty($palavra)) {
                        $qry->whereRaw("(tblgrupoproduto.grupoproduto ilike '%{$palavra}%')");
                    }
                }
            }

            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codgrupoproduto', 'grupoproduto', 'inativo');
            $qry->orderBy('grupoproduto', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codgrupoproduto,
                    'grupoproduto' => $item->grupoproduto,
                    'inativo' => $item->inativo,
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
