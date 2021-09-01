<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\NegocioProdutoBarraRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  NegocioProdutoBarraRepository $repository 
 */
class NegocioProdutoBarraController extends Controller
{

    public function __construct(NegocioProdutoBarraRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Negocio Produto Barra');
        $this->bc->addItem('Negocio Produto Barra', url('negocio-produto-barra'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        // Permissao
        //$this->repository->authorize('listing');
        
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
                    'dir' => 'DESC',
                ]],
            ];
        }
        
        
        // retorna View
        return view('negocio-produto-barra.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }

    /**
     * Monta json para alimentar a Datatable do index
     * 
     * @param  Request $request
     * @return  json
     */
    public function datatable(Request $request) {
        
        // Autorizacao
        //$this->repository->authorize('listing');

        // Grava Filtro para montar o formulario da proxima vez que o index for carregado
        $this->setFiltro([
            'filtros' => $request['filtros'],
            'order' => $request['order'],
        ]);
        
        // Ordenacao
        $columns[0] = 'tblnegocio.codnegocio';
        $columns[1] = 'tblnegocio.codnegocio';
        $columns[2] = 'tblnegocio.lancamento';
        $columns[3] = 'codpessoa';
        $columns[4] = 'codoperacao';
        $columns[5] = 'codfilial';
        $columns[6] = 'tblprodutovariacao.codprodutovariacao';

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
            $quantidade = $reg->quantidade;
            $valor = $reg->valorunitario;
            
            if (!empty($reg->ProdutoBarra->codprodutoembalagem)){
                $quantidade *= $reg->ProdutoBarra->ProdutoEmbalagem->quantidade;
                $valor /= $reg->ProdutoBarra->ProdutoEmbalagem->quantidade;
            }
            
            $data[] = [
                url('negocio', $reg->codnegocio),
                formataCodigo($reg->codnegocio),
                formataData($reg->Negocio->lancamento),
                $reg->Negocio->Pessoa->fantasia,
                $reg->Negocio->NaturezaOperacao->naturezaoperacao,
                $reg->Negocio->Filial->filial,
                $reg->ProdutoBarra->ProdutoVariacao->variacao,
                //$reg->ProdutoBarra->barras,
                formataNumero($valor, 2),
                //$reg->ProdutoBarra->Produto->UnidadeMedida->sigla,
                formataNumero($quantidade, 3)
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
        return view('negocio-produto-barra.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Negocio Produto Barra criado!');
        
        // redireciona para o view
        return redirect("negocio-produto-barra/{$this->repository->model->codnegocioprodutobarra}");
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
        $this->bc->addItem($this->repository->model->codnegocioprodutobarra);
        $this->bc->header = $this->repository->model->codnegocioprodutobarra;
        
        // retorna show
        return view('negocio-produto-barra.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codnegocioprodutobarra, url('negocio-produto-barra', $this->repository->model->codnegocioprodutobarra));
        $this->bc->header = $this->repository->model->codnegocioprodutobarra;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('negocio-produto-barra.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Negocio Produto Barra alterado!');
        
        // redireciona para view
        return redirect("negocio-produto-barra/{$this->repository->model->codnegocioprodutobarra}"); 
    }
    
}
