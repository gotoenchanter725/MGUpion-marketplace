<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ImagemRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\SlimImageCropper\Slim;

use DB;


/**
 * @property  ImagemRepository $repository 
 */
class ImagemController extends Controller
{

    public function __construct(ImagemRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Imagem');
        $this->bc->addItem('Imagem', url('imagem'));
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
        
        $qry = \MGLara\Models\Imagem::query();
        $qry->orderBy('codimagem', 'DESC');
        $filtro['tipo'] = $request->tipo;
        switch ($filtro['tipo']) {
            case 'secao':
                $qry->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblsecaoproduto')
                        ->whereRaw('tblsecaoproduto.codimagem = tblimagem.codimagem');
                });
                break;
            case 'familia':
                $qry->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblfamiliaproduto')
                        ->whereRaw('tblfamiliaproduto.codimagem = tblimagem.codimagem');
                });
                break;
            case 'grupo':
                $qry->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblgrupoproduto')
                        ->whereRaw('tblgrupoproduto.codimagem = tblimagem.codimagem');
                });
                break;
            case 'subgrupo':
                $qry->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblsubgrupoproduto')
                        ->whereRaw('tblsubgrupoproduto.codimagem = tblimagem.codimagem');
                });
                break;
            case 'marca':
                $qry->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblmarca')
                        ->whereRaw('tblmarca.codimagem = tblimagem.codimagem');
                });
                break;
            case 'outras':
                $qry->ativo();
                
                 $qry->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblsecaoproduto')
                        ->whereRaw('tblsecaoproduto.codimagem = tblimagem.codimagem');
                });
                $qry->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblfamiliaproduto')
                        ->whereRaw('tblfamiliaproduto.codimagem = tblimagem.codimagem');
                });
                $qry->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblgrupoproduto')
                        ->whereRaw('tblgrupoproduto.codimagem = tblimagem.codimagem');
                });
                $qry->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblsubgrupoproduto')
                        ->whereRaw('tblsubgrupoproduto.codimagem = tblimagem.codimagem');
                });
                $qry->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblmarca')
                        ->whereRaw('tblmarca.codimagem = tblimagem.codimagem');
                });
                $qry->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblprodutoimagem')
                        ->whereRaw('tblprodutoimagem.codimagem = tblimagem.codimagem');
                });
                //dd($qry->toSql());
                break;
            case 'lixeira':
                $qry->inativo();
                break;
            default:
                $filtro['tipo'] = 'produto';
                $qry->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tblprodutoimagem')
                        ->whereRaw('tblprodutoimagem.codimagem = tblimagem.codimagem');
                });
                break;
        }
        
        $data = $qry->paginate(60);
        
        // retorna View
        return view('imagem.index', ['bc'=>$this->bc, 'filtro'=>$filtro, 'data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $opt = $request->all();
        
        // cria um registro em branco
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('imagem.create', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'opt'=>$opt]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $images = Slim::getImages();
        
        // busca dados do formulario
        $data = $request->all();
        $data['imagem'] = $images[0]['output']['data'];
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        // cria
        if (!$this->repository->create($data)) {
            abort(500);
        }
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Imagem criado!');
        
        
        
        // redireciona para o view
        return $this->redirecionaPeloRelacionamento();
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
        $this->bc->addItem($this->repository->model->codimagem);
        $this->bc->header = $this->repository->model->codimagem;
        
        // retorna show
        return view('imagem.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codimagem, url('imagem', $this->repository->model->codimagem));
        $this->bc->header = $this->repository->model->codimagem;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('imagem.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $images = Slim::getImages();
        
        // busca dados do formulario
        $data = $request->all();
        $data['imagem'] = $images[0]['output']['data'];
        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        // autorizacao
        $this->repository->authorize('update');
        
        // salva
        if (!$this->repository->update(null, $data)) {
            abort(500);
        }
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Imagem alterado!');
        
        // redireciona para view
        return $this->redirecionaPeloRelacionamento();
    }
    
    public function esvaziarLixeira()
    {
        // autorizacao
        $this->repository->authorize('delete');
        
        // apaga
        return ['OK' => $this->repository->esvaziarLixeira()];
    }
    
    public function redirecionaPeloRelacionamento()
    {
        foreach ($this->repository->model->ProdutoImagemS as $model) {
            return redirect("produto/{$model->codproduto}"); 
        }
        
        foreach ($this->repository->model->MarcaS as $model) {
            return redirect("marca/{$model->codmarca}"); 
        }

        foreach ($this->repository->model->SecaoProdutoS as $model) {
            return redirect("secao-produto/{$model->codsecaoproduto}"); 
        }

        foreach ($this->repository->model->FamiliaProdutoS as $model) {
            return redirect("familia-produto/{$model->codfamiliaproduto}"); 
        }
        
        foreach ($this->repository->model->GrupoProdutoS as $model) {
            return redirect("grupo-produto/{$model->codgrupoproduto}"); 
        }
        
        foreach ($this->repository->model->SubGrupoProdutoS as $model) {
            return redirect("sub-grupo-produto/{$model->codsubgrupoproduto}"); 
        }
        
        return redirect("imagem/{$this->repository->model->codimagem}"); 
    }
}
