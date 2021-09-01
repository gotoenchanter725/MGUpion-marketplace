<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use MGLara\Repositories\SubGrupoProdutoRepository;
use MGLara\Repositories\ProdutoRepository;
use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;
use URL;
class SubGrupoProdutoController extends Controller
{
    public function __construct(SubGrupoProdutoRepository $repository, ProdutoRepository $produtoRepository) {
        $this->repository = $repository;
        $this->produtoRepository = $produtoRepository;
        $this->bc = new Breadcrumb('Sub Grupos de Produto');
        $this->bc->addItem('Seções', url('grupo-produto'));
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
        $columns[0] = 'codsubgrupoproduto';
        $columns[1] = 'inativo';
        $columns[2] = 'codsubgrupoproduto';
        $columns[3] = 'subgrupoproduto';
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
                url('sub-grupo-produto', $reg->codsubgrupoproduto),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codsubgrupoproduto),
                $reg->subgrupoproduto,
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
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('sub-grupo-produto.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        return redirect("sub-grupo-produto/{$this->repository->model->codsubgrupoproduto}"); 
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
        $this->bc->addItem($this->repository->model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto, url('secao-produto', $this->repository->model->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto));
        $this->bc->addItem($this->repository->model->GrupoProduto->FamiliaProduto->familiaproduto, url('familia-produto', $this->repository->model->GrupoProduto->FamiliaProduto->codfamiliaproduto));
        $this->bc->addItem($this->repository->model->GrupoProduto->grupoproduto, url('grupo-produto', $this->repository->model->GrupoProduto->codgrupoproduto));
        $this->bc->addItem($this->repository->model->subgrupoproduto);

        $this->bc->header = $this->repository->model->subgrupoproduto;
        
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
        
        //dd($filtro);
        // retorna show
        return view('sub-grupo-produto.show', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'filtro'=>$filtro]);
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
        $this->bc->addItem($this->repository->model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto, url('secao-produto', $this->repository->model->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto));
        $this->bc->addItem($this->repository->model->GrupoProduto->FamiliaProduto->familiaproduto, url('familia-produto', $this->repository->model->GrupoProduto->FamiliaProduto->codfamiliaproduto));
        $this->bc->addItem($this->repository->model->GrupoProduto->grupoproduto, url('grupo-produto', $this->repository->model->GrupoProduto->codgrupoproduto));
        $this->bc->addItem($this->repository->model->subgrupoproduto, url($this->repository->model->codsubgrupoproduto));

        $this->bc->header = $this->repository->model->subgrupoproduto;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('sub-grupo-produto.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        return redirect("sub-grupo-produto/{$this->repository->model->codsubgrupoproduto}"); 
    }

    public function datatableProduto(Request $request) {

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
        //dd($request['filtros']);
        // Pega listagem dos registros
        $regs = $this->produtoRepository->listing($request['filtros'], $sort, $request['start'], $request['length']);
        
        // Monta Totais
        $recordsTotal = $regs['recordsTotal'];
        $recordsFiltered = $regs['recordsFiltered'];
        
        // Formata registros para exibir no data table
        $data = [];
        foreach ($regs['data'] as $reg) {
            $data[] = [
                url('produto', $reg->codproduto),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codproduto),
                $reg->produto,
                empty($reg->codprodutoimagem)?URL::asset('public/imagens/semimagem.jpg'):URL::asset("public/imagens/{$reg->ProdutoImagem->Imagem->arquivo}"),
                $reg->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto .' » '. $reg->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto .' » '. $reg->SubGrupoProduto->GrupoProduto->grupoproduto .' » '.$reg->SubGrupoProduto->subgrupoproduto,
                $reg->Marca->marca,
                $reg->UnidadeMedida->sigla,
                $reg->referencia,
                formataNumero($reg->preco),
            ];
        }
        
        // Envelopa os dados no formato do data table
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorna o JSON
        return collect($ret);
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
                'id' => $item->codsubgrupoproduto,
                'subgrupoproduto' => $item->subgrupoproduto,
                'inativo' => $item->inativo,
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = $this->repository->model->where('codgrupoproduto',  $request->codgrupoproduto);
            
            if(!empty($params['term'])) {
                foreach (explode(' ', $params['term']) as $palavra) {
                    if (!empty($palavra)) {
                        $qry->whereRaw("(tblsubgrupoproduto.subgrupoproduto ilike '%{$palavra}%')");
                    }
                }
            }

            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codsubgrupoproduto', 'subgrupoproduto', 'inativo');
            $qry->orderBy('subgrupoproduto', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codsubgrupoproduto,
                    'subgrupoproduto' => $item->subgrupoproduto,
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
