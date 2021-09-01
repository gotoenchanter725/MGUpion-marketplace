<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use MGLara\Repositories\GeradorCodigoRepository;
use MGLara\Library\Breadcrumb\Breadcrumb;

/**
 * 
 * @property GeradorCodigoRepository $repository 
 */

class GeradorCodigoController extends Controller
{

    public function __construct(GeradorCodigoRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Gerador de Código');
        $this->bc->addItem('Gerador de Código', url('gerador-codigo'));
    }
    
    public function index() {
        $tabelas = $this->repository->buscaTabelas();
        return view('gerador-codigo.index', ['tabelas' => $tabelas, 'bc' => $this->bc]);
    }

    public function show(Request $request, $id) {
        $model = $this->repository->buscaArquivoModel($id);
        $cols_original = $this->repository->buscaCamposTabela($id);
        $cols = [];
        foreach ($cols_original as $col) {
            $cols[$col->column_name] = $col->column_name;
        }
        
        return view('gerador-codigo.show', ['model'=>$model, 'tabela' => $id, 'cols' => $cols]);
    }
    
    public function showModel(Request $request, $tabela) {
        $conteudo = $this->repository->geraModel($tabela, $request->model, $request->titulo);
        return view('gerador-codigo.model', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
        ]);
    }

    public function storeModel(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaModel($tabela, $request->model, $request->titulo)];
    }
    
    public function showRepository(Request $request, $tabela) {
        $conteudo = $this->repository->geraRepository($tabela, $request->model, $request->titulo);
        return view('gerador-codigo.repository', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
        ]);
    }

    public function storeRepository(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaRepository($tabela, $request->model, $request->titulo)];
    }
    
    public function showPolicy(Request $request, $tabela) {
        $conteudo = $this->repository->geraPolicy($request->model);
        $registrada = $this->repository->verificaRegistroPolicy($request->model);
        $string_registro = $this->repository->stringRegistroPolicy($request->model);
        return view('gerador-codigo.policy', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
            'registrada'=>$registrada,
            'string_registro'=>$string_registro,
        ]);
    }

    public function storePolicy(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaPolicy($request->model)];
    }
    
    public function showController(Request $request, $tabela) {
        $conteudo = $this->repository->geraController($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo);
        $registrado = $this->repository->verificaRegistroRota($request->url);
        $string_registro = $this->repository->stringRegistroRota($request->model, $request->url);
        return view('gerador-codigo.controller', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
            'url'=>$request->url,
            'coluna_titulo'=>$request->coluna_titulo,
            'registrado'=>$registrado,
            'string_registro'=>$string_registro,
        ]);
    }

    public function storeController(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaController($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo)];
    }
    
    public function showViewIndex(Request $request, $tabela) {
        $conteudo = $this->repository->geraViewIndex($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo);
        return view('gerador-codigo.view-index', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
            'url'=>$request->url,
            'coluna_titulo'=>$request->coluna_titulo,
        ]);
    }

    public function storeViewIndex(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaViewIndex($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo)];
    }
    
    public function showViewShow(Request $request, $tabela) {
        $conteudo = $this->repository->geraViewShow($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo);
        return view('gerador-codigo.view-show', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
            'url'=>$request->url,
            'coluna_titulo'=>$request->coluna_titulo,
        ]);
    }

    public function storeViewShow(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaViewShow($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo)];
    }
    
    public function showViewCreate(Request $request, $tabela) {
        $conteudo = $this->repository->geraViewCreate($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo);
        return view('gerador-codigo.view-create', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
            'url'=>$request->url,
            'coluna_titulo'=>$request->coluna_titulo,
        ]);
    }

    public function storeViewCreate(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaViewCreate($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo)];
    }
    
    public function showViewEdit(Request $request, $tabela) {
        $conteudo = $this->repository->geraViewEdit($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo);
        return view('gerador-codigo.view-edit', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
            'url'=>$request->url,
            'coluna_titulo'=>$request->coluna_titulo,
        ]);
    }

    public function storeViewEdit(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaViewEdit($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo)];
    }
    
    public function showViewForm(Request $request, $tabela) {
        $conteudo = $this->repository->geraViewForm($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo);
        return view('gerador-codigo.view-form', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
            'url'=>$request->url,
            'coluna_titulo'=>$request->coluna_titulo,
        ]);
    }

    public function storeViewForm(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaViewForm($tabela, $request->model, $request->titulo, $request->url, $request->coluna_titulo)];
    }
    
}
