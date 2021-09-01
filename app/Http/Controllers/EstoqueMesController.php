<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\EstoqueMesRepository;
use MGLara\Repositories\EstoqueLocalRepository;
use MGLara\Repositories\ProdutoVariacaoRepository;
use MGLara\Repositories\EstoqueLocalProdutoVariacaoRepository;
use MGLara\Repositories\EstoqueSaldoRepository;

use MGLara\Models\EstoqueLocal;
use MGLara\Models\ProdutoVariacao;
use MGLara\Models\EstoqueLocalProdutoVariacao;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  EstoqueMesRepository $repository 
 */
class EstoqueMesController extends Controller
{

    public function __construct(EstoqueMesRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Produto');
        $this->bc->addItem('Produto', url('produto'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = $this->repository->findOrFail($id);
        
        return $this->showKardex(
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal, 
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao, 
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao, 
            $model->EstoqueSaldo, 
            $model,
            $model->EstoqueSaldo->fiscal,
            $model->mes->year,
            $model->mes->month
        );
        
    }
    
    public function kardex(Request $request, int $codestoquelocal, int $codprodutovariacao, string $fiscal, int $ano, int $mes)
    {
        
        $fiscal = ($fiscal == 'fiscal');
        
        $repo_el = new EstoqueLocalRepository();
        $el = $repo_el->findOrFail($codestoquelocal);
        
        $repo_pv = new ProdutoVariacaoRepository();
        $pv = $repo_pv->findOrFail($codprodutovariacao);
        
        $repo_elpv = new EstoqueLocalProdutoVariacaoRepository();
        $es = null;
        $em = null;
        if ($elpv = $repo_elpv->busca($codestoquelocal, $codprodutovariacao)) {
            $repo_es = new EstoqueSaldoRepository();
            
            if ($es = $repo_es->busca($elpv, $fiscal)) {
                $repo_mes = new EstoqueMesRepository();
                $em = $repo_mes->busca($es, Carbon::create($ano, $mes, 1));
            }
        }
        
        return $this->showKardex($el, $pv, $elpv, $es, $em, $fiscal, $ano, $mes);
        
    }
    
    private function showKardex (EstoqueLocal $el, ProdutoVariacao $pv, $elpv, $es, $em, bool $fiscal, int $ano, int $mes)
    {
        //autorizacao
        $this->repository->authorize('view');
        
        $filtro['order'] = [[
            'column' => 1,
            'dir' => 'ASC',
        ]];
        
        $pvs = $pv->Produto->ProdutoVariacaoS()->orderByRaw('variacao asc nulls first')->get();
        
        // breadcrumb
        $this->bc->addItem($pv->Produto->produto, url('produto', $pv->codproduto));
        $this->bc->addItem('Kardex');
        $this->bc->header = 'Kardex ' . $pv->Produto->produto;
        
        $movs = [];
        if (!empty($em)) {
            $movs = $this->repository->movimentoKardex($em);
        }
        
        $repo_es = new EstoqueSaldoRepository();

        $ems = [];
        if (!empty($es)) {
            $mesAtual = Carbon::create($ano, $mes, 1);
            $ems = $repo_es->meses($mesAtual, $es, 3, 6);
        }
        
        $slds = $repo_es->saldosPorFisicoFiscal($pv->codproduto);
        $els = $repo_es->saldosPorLocal($pv->codproduto, $fiscal);
        $pvs = $repo_es->saldosPorVariacao($pv->codproduto, $fiscal, $el->codestoquelocal);

        // retorna show
        return view('estoque-mes.show', [
            'bc'=>$this->bc, 
            
            // Registro corrente
            'pv'=>$pv, 
            'el'=>$el, 
            'elpv'=>$elpv, 
            'es'=>$es, 
            'em'=>$em,
            'movs'=>$movs,
            
            // Registros da navegacao
            'slds'=>$slds, 
            'els'=>$els, 
            'pvs'=>$pvs, 
            'ems'=>$ems, 

            // da url
            'fiscal'=>$fiscal, 
            'ano'=>$ano, 
            'mes'=>$mes, 
            
            
            //'anteriores'=>$anteriores, 
            //'proximos'=>$proximos, 
            //'filtro'=>$filtro, 
            //'model'=>$this->repository->model
        ]);
    }
    
}
