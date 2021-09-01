<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use MGLara\Repositories\EstoqueMovimentoRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  EstoqueMovimentoRepository $repository 
 */
class EstoqueMovimentoController extends Controller
{

    public function __construct(EstoqueMovimentoRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Estoque Movimento');
        $this->bc->addItem('Estoque Movimento', url('estoque-movimento'));
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
        
        // autoriza
        $this->repository->authorize('create');

        $model = $this->repository->model;
        
        if (empty($request->data) && !empty($request->mes) && !empty($request->ano)) {
            $data = Carbon::create($request->ano, $request->mes);
            $model->data = $data->endOfMonth();
        }
        
        if (!empty($request->fiscal)) {
            $model->fiscal = ($request->fiscal == 'fiscal');
        }
        
        $model->codestoquelocal = $request->codestoquelocal;
        $model->codprodutovariacao = $request->codprodutovariacao;
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('estoque-movimento.create', ['bc'=>$this->bc, 'model'=>$model]);
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
        $data['manual'] = true;
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        DB::BeginTransaction();
        
        // cria
        if (!$this->repository->create()) {
            abort(500);
        }
        
        DB::Commit();
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Estoque Movimento criado!');
        
        // redireciona para o view
        return redirect("estoque-mes/{$this->repository->model->codestoquemes}");
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
        
        // se nao for registro manual, nao deixa atualizar
        if (!$this->repository->model->manual) {
            abort(403);
        }

        // se tentar editar o movimento de destino, joga para o movimento de origem
        if (!empty($this->repository->model->codestoquemovimentoorigem)) {
            return redirect("estoque-movimento/{$this->repository->model->codestoquemovimentoorigem}/edit"); 
        }
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->codestoquemovimento, url('estoque-movimento', $this->repository->model->codestoquemovimento));
        $this->bc->header = $this->repository->model->codestoquemovimento;
        $this->bc->addItem('Alterar');
        
        
        $model = $this->repository->model;
        $model->codestoquelocal = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal;
        $model->codprodutovariacao = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao;
        $model->codproduto = $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto;
        $model->saldoquantidade = $model->EstoqueMes->EstoqueSaldo->saldoquantidade;
        $model->customedio = $model->EstoqueMes->EstoqueSaldo->customedio;
        $model->transferencia = $model->EstoqueMovimentoTipo->transferencia;
        
        if ($dest = $this->repository->movimentoDestino($model)) {
            $model->codestoquelocaldestino = $dest->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal;
            $model->codprodutovariacaodestino = $dest->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao;
            $model->codprodutodestino = $dest->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto;
            $model->saldoquantidadedestino = $dest->EstoqueMes->EstoqueSaldo->saldoquantidade;
            $model->customediodestino = $dest->EstoqueMes->EstoqueSaldo->customedio;
        }
        
        // retorna formulario edit
        return view('estoque-movimento.edit', ['bc'=>$this->bc, 'model'=>$model]);
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
        DB::BeginTransaction();
        
        $request->manual = true;
        parent::update($request, $id);
        
        DB::Commit();
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Estoque Movimento alterado!');
        
        // redireciona para view
        return redirect("estoque-mes/{$this->repository->model->codestoquemes}"); 
    }
    
}
