<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use MGLara\Repositories\ValeCompraModeloRepository;
use MGLara\Repositories\ValeCompraModeloProdutoBarraRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;


/**
 * @property  ValeCompraModeloRepository $repository 
 * @property  ValeCompraModeloProdutoBarraRepository $valeCompraModeloProdutoBarraRepository 
 */
class ValeCompraModeloController extends Controller
{

    public function __construct(ValeCompraModeloRepository $repository, ValeCompraModeloProdutoBarraRepository $valeCompraModeloProdutoBarraRepository) {
        $this->repository = $repository;
        $this->valeCompraModeloProdutoBarraRepository = $valeCompraModeloProdutoBarraRepository;
        $this->bc = new Breadcrumb('Vale Compra Modelo');
        $this->bc->addItem('Vale Compra Modelo', url('vale-compra-modelo'));
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
                    'column' => 3, 
                    'dir' => 'ASC'
                ]],
            ];
        }
        
        // retorna View
        return view('vale-compra-modelo.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codvalecompramodelo';
        $columns[1] = 'inativo';
        $columns[2] = 'codvalecompramodelo';
        $columns[3] = 'modelo';
        $columns[4] = 'total';
        $columns[5] = 'codpessoafavorecido';
        $columns[6] = 'ano';
        $columns[7] = 'turma';
        $columns[8] = 'observacoes';

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
                url('vale-compra-modelo', $reg->codvalecompramodelo),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codvalecompramodelo),
                $reg->modelo,
                $reg->total,
                $reg->PessoaFavorecido->fantasia,
                $reg->ano,
                $reg->turma,
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
        return view('vale-compra-modelo.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        // busca dados do formulario
        $data = $request->all();
        //dd($data);
        // preenche dados 
        $model = $this->repository->new($data);
        $model->totalprodutos = array_sum($data['item_total']);
        $model->total = $model->totalprodutos - $model->desconto;
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        // autoriza
        $this->repository->authorize('create');
        //dd($model);
        if ($model->save()) {
            foreach ($data['item_codprodutobarra'] as $key => $codprodutobarra) {
                if (empty($codprodutobarra)) {
                    continue;
                }
                
                $prod = $this->valeCompraModeloProdutoBarraRepository->new([
                    'codvalecompramodelo' => $model->codvalecompramodelo,
                    'codprodutobarra' => $codprodutobarra,
                    'quantidade' => $data['item_quantidade'][$key],
                    'preco' => $data['item_preco'][$key],
                    'total' => $data['item_total'][$key],
                ]);
                
                $prod->save();
            }
        } else {
            abort(500);
        }
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Vale Compra Modelo criado!');
        
        DB::commit();
        
        // redireciona para o view
        return redirect("vale-compra-modelo/{$this->repository->model->codvalecompramodelo}");
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
        $this->bc->addItem($this->repository->model->modelo);
        $this->bc->header = $this->repository->model->modelo;
        
        // retorna show
        return view('vale-compra-modelo.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->modelo, url('vale-compra-modelo', $this->repository->model->codvalecompramodelo));
        $this->bc->header = $this->repository->model->modelo;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('vale-compra-modelo.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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

        // Valida dados
        $data = $request->all();
        $this->repository->fill($data);
        $this->repository->model->totalprodutos = array_sum($data['item_total']);
        $this->repository->model->total = $this->repository->model->totalprodutos - $this->repository->model->desconto;     
        
        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        // autorizacao
        $this->repository->fill($data);
        $this->repository->authorize('update');
        
        DB::beginTransaction();
        
        if ($this->repository->update()) {
            $codvalecompramodeloprodutobarra = [];
            foreach ($data['item_codprodutobarra'] as $key => $codprodutobarra) {
                if (empty($codprodutobarra)) {
                    continue;
                }
                $data_prod = [
                    'codvalecompramodelo' => $this->repository->model->codvalecompramodelo,
                    'codprodutobarra' => $codprodutobarra,
                    'quantidade' => $data['item_quantidade'][$key],
                    'preco' => $data['item_preco'][$key],
                    'total' => $data['item_total'][$key],
                ];
    
                if (!empty($data['item_codvalecompramodeloprodutobarra'][$key])) {
                    $this->valeCompraModeloProdutoBarraRepository->findOrFail($data['item_codvalecompramodeloprodutobarra'][$key]);
                    $this->valeCompraModeloProdutoBarraRepository->fill($data_prod);
                } else {
                    $this->valeCompraModeloProdutoBarraRepository->new($data_prod);
                }
                
                $this->valeCompraModeloProdutoBarraRepository->model->save();
                $codvalecompramodeloprodutobarra[] = $this->valeCompraModeloProdutoBarraRepository->model->codvalecompramodeloprodutobarra;
            }
            
            $this->repository->model->ValeCompraModeloProdutoBarraS()->whereNotIn('codvalecompramodeloprodutobarra', $codvalecompramodeloprodutobarra)->delete();
            
        }
        DB::commit();        
        
        // salva
        if (!$this->repository->update()) {
            abort(500);
        }
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Vale Compra Modelo alterado!');
        
        // redireciona para view
        return redirect("vale-compra-modelo/{$this->repository->model->codvalecompramodelo}");         
    }
    
    public function destroy($id)
    {
        DB::beginTransaction();
        // Busca o registro
        $this->repository->findOrFail($id);

        // autorizacao
        $this->repository->authorize('delete');

        foreach ($this->repository->model->ValeCompraModeloProdutoBarraS as $vcmpb) {
            $vcmpb->delete();
        }

        // se esta sendo usado
        if ($mensagem = $this->repository->used()) {
            return ['OK' => false, 'mensagem' => $mensagem];
        }

        DB::commit();
        
        // apaga
        return ['OK' => $this->repository->delete()];
    }    
}
