<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use MGLara\Repositories\EstoqueSaldoConferenciaRepository;
use MGLara\Repositories\EstoqueSaldoRepository;
use MGLara\Repositories\ProdutoBarraRepository;
use MGLara\Repositories\EstoqueLocalProdutoVariacaoRepository;
use MGLara\Repositories\EstoqueMesRepository;

use MGLara\Models\ProdutoVariacao;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  EstoqueSaldoConferenciaRepository $repository 
 * @property  EstoqueSaldoRepository $repositorySaldo
 */
class EstoqueSaldoConferenciaController extends Controller
{

    public function __construct(EstoqueSaldoConferenciaRepository $repository, EstoqueSaldoRepository $repositorySaldo) {
        $this->repository = $repository;
        $this->repositorySaldo = $repositorySaldo;
        $this->bc = new Breadcrumb('Conferência de Saldo de Estoque');
        $this->bc->addItem('Conferência de Saldo de Estoque', url('estoque-saldo-conferencia'));
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
                    'dir' => 'DESC'
                ]],
            ];
        }
        
        // retorna View
        return view('estoque-saldo-conferencia.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'tblestoquesaldoconferencia.codestoquesaldoconferencia';
        $columns[1] = 'tblestoquesaldoconferencia.inativo';
        $columns[2] = 'tblestoquesaldoconferencia.codestoquesaldoconferencia';
        $columns[3] = 'tblproduto.produto';
        $columns[4] = 'tblprodutovariacao.variacao';
        $columns[5] = 'tblestoquelocal.estoquelocal';
        $columns[6] = 'tblestoquesaldoconferencia.quantidadeinformada';
        $columns[7] = 'tblestoquesaldoconferencia.customedioinformado';
        $columns[8] = 'tblestoquesaldoconferencia.data';
        $columns[9] = 'tblusuario.usuario';

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
                url('estoque-saldo-conferencia', $reg->codestoquesaldoconferencia),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codestoquesaldoconferencia),
                $reg->produto,
                $reg->variacao,
                $reg->estoquelocal,
                formataNumero($reg->quantidadeinformada, 3),
                formataNumero($reg->customedioinformado, 6),
                formataData($reg->data, 'L'),
                $reg->usuario,
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
        $data = [];
        if ($codestoquesaldo = $request->codestoquesaldo) {
            $sld = $this->repositorySaldo->findOrFail($codestoquesaldo);
            $barras = null;
            if ($pb = $sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->ProdutoBarras()->whereNull('codprodutoembalagem')->first()) {
                $barras = $pb->barras;
            }
            $data = [
                'codestoquesaldo' => $codestoquesaldo,
                'codestoquelocal' => $sld->EstoqueLocalProdutoVariacao->codestoquelocal,
                'barras' => $barras,
                'codproduto' => $sld->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto,
                'codprodutovariacao' => $sld->EstoqueLocalProdutoVariacao->codprodutovariacao,
                'fiscal' => $sld->fiscal,
            ];
            
        }
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('estoque-saldo-conferencia.create', ['bc'=>$this->bc, 'data'=>$data]);
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
        
        $data['fiscal'] = ($data['fiscal']==1);
        $data['data'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['data']);
        
        if (empty($data['codestoquesaldo'])) {
            $saldo = $this->repositorySaldo->buscaOuCria($data['codprodutovariacao'], $data['codestoquelocal'], $data['fiscal']);
            $data['codestoquesaldo'] = $saldo->codestoquesaldo;
        }
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        DB::beginTransaction();
        
        // cria
        if (!$this->repository->create()) {
            abort(500);
        }
        
        $elpv_repo = new EstoqueLocalProdutoVariacaoRepository;
        $elpv_repo->model = $this->repository->model->EstoqueSaldo->EstoqueLocalProdutoVariacao;
        $elpv_repo->fill([
            'corredor' => $data['corredor'],
            'prateleira' => $data['prateleira'],
            'coluna' => $data['coluna'],
            'bloco' => $data['bloco'],
        ]);
        $elpv_repo->update();
        
        DB::commit();
        
        $repoMes = new EstoqueMesRepository();
        $mes = $repoMes->buscaOuCria($this->repository->model->EstoqueSaldo, $this->repository->model->data);
        
        return [
            'OK' => true,
            'codestoquemes' => $mes->codestoquemes
        ];
        
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
        $this->bc->addItem(formataCodigo($this->repository->model->codestoquesaldoconferencia));
        $this->bc->header = formataCodigo($this->repository->model->codestoquesaldoconferencia);
        
        // retorna show
        return view('estoque-saldo-conferencia.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }
    
    public function saldos(Request $request)
    {
        if (empty($request->codprodutovariacao)) {
            $repoBarra = new ProdutoBarraRepository();
            if (!$pb = $repoBarra->buscaPorBarras($request->barras)) {
                abort(404);
            }
            $pv = $pb->ProdutoVariacao;
        } else {
            $pv = ProdutoVariacao::findOrFail($request->codprodutovariacao);
        }
        
        $fiscal = ($request->fiscal == 1);
        $pivot = $this->repositorySaldo->pivotProduto($pv->codproduto, $fiscal);
        
        $data = [
            'data' => Carbon::now(),
            'codprodutovariacao' => $pv->codprodutovariacao,
            'codestoquelocal' => $request->codestoquelocal,
            'codestoquesaldo' => null,
            'fiscal' => ($fiscal)?1:0,
            'quantidadeinformada' => null,
            'customedioinformado' => null,
            'observacoes' => null,
            'corredor' => null,
            'prateleira' => null,
            'coluna' => null,
            'bloco' => null,
        ];
        
        $repoElpv = new EstoqueLocalProdutoVariacaoRepository();
        if ($loc = $repoElpv->busca($request->codestoquelocal, $pv->codprodutovariacao)) {
            
            $data['corredor'] = $loc->corredor;
            $data['prateleira'] = $loc->prateleira;
            $data['coluna'] = $loc->coluna;
            $data['bloco'] = $loc->bloco;
            
            if ($saldo = $this->repositorySaldo->busca($loc, $fiscal)) {
                $data['quantidadeinformada'] = $saldo->saldoquantidade;
                $data['customedioinformado'] = $saldo->customedio;
                $data['codestoquesaldo'] = $saldo->codestoquesaldo;

            }
            
        }
        
        // retorna show
        return view('estoque-saldo-conferencia.saldos', [
            'pivot'=>$pivot, 
            'saldo'=>$saldo, 
            'codprodutovariacao' => $pv->codprodutovariacao, 
            'codestoquelocal' => $request->codestoquelocal, 
            'fiscal' => $fiscal, 
            'data' => $data,
        ]);
        
    }
    
}
