<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ProdutoEmbalagemRepository;
use MGLara\Repositories\ProdutoRepository;
use MGLara\Repositories\ProdutoHistoricoPrecoRepository;
use MGLara\Repositories\ProdutoBarraRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use DB;
use Carbon\Carbon;

/**
 * @property  ProdutoEmbalagemRepository $repository 
 * @property  ProdutoRepository $produtoRepository 
 * @property  ProdutoHistoricoPrecoRepository $produtoHistoricoPrecoRepository 
 * @property  ProdutoBarraRepository $produtoBarraRepository 
 */
class ProdutoEmbalagemController extends Controller
{

    public function __construct(
            ProdutoEmbalagemRepository $repository, 
            ProdutoRepository $produtoRepository, 
            ProdutoBarraRepository $produtoBarraRepository, 
            ProdutoHistoricoPrecoRepository $produtoHistoricoPrecoRepository
        ) {
        $this->repository = $repository;
        $this->produtoRepository = $produtoRepository;
        $this->produtoBarraRepository = $produtoBarraRepository;
        $this->produtoHistoricoPrecoRepository = $produtoHistoricoPrecoRepository;
        $this->bc = new Breadcrumb('Produto');
        $this->bc->addItem('Produto', url('produto'));
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
        return view('produto-embalagem.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codprodutoembalagem';
        $columns[1] = 'inativo';
        $columns[2] = 'codprodutoembalagem';
        $columns[3] = 'codprodutoembalagem';
        $columns[4] = 'codproduto';
        $columns[5] = 'codunidademedida';
        $columns[6] = 'quantidade';
        $columns[7] = 'preco';

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
                url('produto-embalagem', $reg->codprodutoembalagem),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codprodutoembalagem),
                $reg->codprodutoembalagem,
                $reg->codproduto,
                $reg->codunidademedida,
                $reg->quantidade,
                $reg->preco,
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
    public function create(Request $request)
    {
        // cria um registro em branco
        $this->repository->new();
        $produto = $this->produtoRepository->findOrFail($request->codproduto);
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem($this->produtoRepository->model->produto, url('produto', $this->produtoRepository->model->codproduto));
        $this->bc->addItem('Nova Embalagem');

        
        // retorna view
        return view('produto-embalagem.create', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'produto'=>$produto]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // busca dados do formulario
        $data = $request->all();

        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        DB::beginTransaction();

        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        try {
            if (!$this->repository->create()) {
                throw new Exception('Erro ao Criar Embalagem!');
            }

            $i = 0;
            
            foreach ($this->repository->model->Produto->ProdutoVariacaoS as $pv)
            {
                $this->produtoBarraRepository->new([
                    'codproduto'            => $this->repository->model->codproduto,
                    'codprodutovariacao'    => $pv->codprodutovariacao,
                    'codprodutoembalagem'   => $this->repository->model->codprodutoembalagem
                ]);
                
                if (!$this->produtoBarraRepository->save()) {
                    throw new Exception('Erro ao Criar Barras!');
                }
                
                $i++;
            }
            
            DB::commit();
            Session::flash('flash_create', "Embalagem '{$this->repository->model->descricao}' criada!");
            return redirect("produto/{$this->repository->model->codproduto}");
            
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        return redirect("produto/{$this->repository->model->codproduto}");
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
        $this->bc->addItem($this->repository->model->codprodutoembalagem);
        $this->bc->header = $this->repository->model->codprodutoembalagem;
        
        // retorna show
        return view('produto-embalagem.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->produto->produto, url('produto', $this->repository->model->produto->codproduto));
        $this->bc->header = $this->repository->model->descricao;
        $this->bc->addItem($this->repository->model->descricao);
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('produto-embalagem.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        
        // autorizacao
        $this->repository->fill($data);
        $this->repository->authorize('update');
        
        DB::beginTransaction();
        
        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        try {
            $preco = $this->repository->model->getOriginal('preco');
            
            if (!$this->repository->update()) {
                throw new Exception('Erro ao alterar Embalagem!');
            }

            if($preco != $this->repository->model->preco) {
                $this->produtoHistoricoPrecoRepository->new([
                    'codproduto'            => $this->repository->model->Produto->codproduto,
                    'codprodutoembalagem'   => $this->repository->model->codprodutoembalagem,
                    'precoantigo'           => $preco,
                    'preconovo'             => $this->repository->model->preco,
                ]);
                
                if (!$this->produtoHistoricoPrecoRepository->save()) {
                    throw new Exception('Erro ao gravar Historico!');
                }
            }
            
            DB::commit();
            Session::flash('flash_update', "Embalagem '{$this->repository->model->descricao}' alterada!");
            return redirect("produto/{$this->repository->model->codproduto}");        
            
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $this->repository->validator);
        }        
    }
}
