<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\DominioRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  DominioRepository $repository 
 */
class DominioController extends Controller
{

    public function __construct(DominioRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Exportação Domínio');
        $this->bc->addItem('Exportação Domínio', url('dominio'));
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
        //$this->bc->addItem('Listagem');
        
        // Filtro da listagem
        if (!$parametros = $this->getFiltro()) {
            $parametros = [
                'data_inicial' => new Carbon('first day of last month'),
                'data_final' => new Carbon('last day of last month'),
                'arquivo_estoque' => '{ano}{mes}-{empresa}-Estoque.txt',
            ];
        }
        
        // retorna View
        return view('dominio.index', ['bc'=>$this->bc, 'parametros'=>$parametros]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function exportaEstoque(Request $request) {
        
        // Permissao
        $this->repository->authorize('exportaEstoque');
        
        // Ultimo dia do ano anterior
        $mes = (new Carbon($request->data))->addYear(-1)->lastOfYear();
        
        // exporta produtos
        $ret = $this->repository->exportaProdutos($request->arquivo_estoque, $request->codfilial, true, $mes);
        
        return [
            'OK' => $ret
        ];
        
        
        /*
        // Permissao
        $this->repository->authorize('exportaEstoque');
        
        // Breadcrumb
        $this->bc->addItem('Listagem');
        
        // Filtro da listagem
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'inativo' => 1,
            ];
        }
        
        // retorna View
        return view('dominio.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
         * 
         */
    }


    
}
