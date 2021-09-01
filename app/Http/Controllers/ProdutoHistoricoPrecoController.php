<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ProdutoHistoricoPrecoRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

/**
 * @property  ProdutoHistoricoPrecoRepository $repository 
 */
class ProdutoHistoricoPrecoController extends Controller
{

    public function __construct(ProdutoHistoricoPrecoRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Produto Historico Preco');
        $this->bc->addItem('Produto Historico Preco');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        // Permissao
        $this->repository->authorize('listing');
                
        // Filtro da listagem
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'filtros' => [],
                'order' => [[
                    'column' => 11,
                    'dir' => 'DESC'
                ]],
            ];
        }

        // retorna View
        return view('produto-historico-preco.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codprodutohistoricopreco';
        $columns[1] = 'codprodutohistoricopreco';
        $columns[2] = 'codproduto';
        $columns[3] = 'produto';
        $columns[4] = 'codprodutoembalagem';
        $columns[5] = 'referencia';
        $columns[6] = 'codmarca';
        $columns[7] = 'tblproduto.preco';
        $columns[8] = 'precoantigo';
        $columns[9] = 'preconovo';
        $columns[10] = 'codusuariocriacao';
        $columns[11] = 'tblprodutohistoricopreco.criacao'; // ok

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
            if(isset($row->codprodutoembalagem)){
                $embalagem = $reg->ProdutoEmbalagem->UnidadeMedida->sigla  .'/'. formataNumero($row->ProdutoEmbalagem->quantidade, 0);
            }else{
                $embalagem = $reg->Produto->UnidadeMedida->sigla;
            }
            $data[] = [
                url('produto', $reg->Produto->codproduto),
                formataCodigo($reg->codprodutohistoricopreco),
                formataCodigo($reg->Produto->codproduto),
                $reg->Produto->produto,
                $embalagem,
                $reg->Produto->referencia,
                $reg->Produto->Marca->marca,
                $reg->ProdutoEmbalagem->preco ?? formataNumero($reg->Produto->preco),
                formataNumero($reg->precoantigo),
                formataNumero($reg->preconovo),
                $reg->UsuarioCriacao->usuario,
                formataData($reg->criacao, 'L')
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
        return view('produto-historico-preco.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Produto Historico Preco criado!');
        
        // redireciona para o view
        return redirect("produto-historico-preco/{$this->repository->model->codprodutohistoricopreco}");
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
        $this->bc->addItem($this->repository->model->codproduto);
        $this->bc->header = $this->repository->model->codproduto;
        
        // retorna show
        return view('produto-historico-preco.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codproduto, url('produto-historico-preco', $this->repository->model->codprodutohistoricopreco));
        $this->bc->header = $this->repository->model->codproduto;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('produto-historico-preco.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Produto Historico Preco alterado!');
        
        // redireciona para view
        return redirect("produto-historico-preco/{$this->repository->model->codprodutohistoricopreco}"); 
    }
    
}
