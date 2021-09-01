<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;
use DB;

use MGLara\Repositories\ValeCompraRepository;
use MGLara\Repositories\ValeCompraModeloRepository;
use MGLara\Repositories\ValeCompraProdutoBarraRepository;
use MGLara\Repositories\PessoaRepository;
use MGLara\Repositories\ValeCompraFormaPagamentoRepository;
use MGLara\Repositories\TituloRepository;
use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;
use MGLara\Library\EscPrint\EscPrintValeCompra;

/**
 * @property  ValeCompraRepository $repository 
 * @property  ValeCompraModeloRepository $valeCompraModeloRepository 
 * @property  ValeCompraProdutoBarraRepository $valeCompraModeloRepository 
 * @property  PessoaRepository $pessoaRepository 
 * @property  ValeCompraFormaPagamentoRepository $valeCompraFormaPagamentoRepository
 * @property  TituloRepository $tituloRepository
 */
class ValeCompraController extends Controller
{

    public function __construct(ValeCompraRepository $repository, ValeCompraModeloRepository $valeCompraModeloRepository, ValeCompraProdutoBarraRepository $valeCompraProdutoBarraRepository, PessoaRepository $pessoaRepository, ValeCompraFormaPagamentoRepository $valeCompraFormaPagamentoRepository, TituloRepository $tituloRepository) {
        $this->repository = $repository;
        $this->valeCompraModeloRepository = $valeCompraModeloRepository;
        $this->valeCompraProdutoBarraRepository = $valeCompraProdutoBarraRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->valeCompraFormaPagamentoRepository = $valeCompraFormaPagamentoRepository;
        $this->tituloRepository = $tituloRepository;
        $this->bc = new Breadcrumb('Vale Compras');
        $this->bc->addItem('Vale Compras', url('vale-compra'));
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
                    'column' => 9, 
                    'dir' => 'DESC'
                ]],
            ];
        }
        
        // retorna View
        return view('vale-compra.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codvalecompra';
        $columns[1] = 'inativo';
        $columns[2] = 'codvalecompra';
        $columns[3] = 'aluno';
        $columns[4] = 'turma';
        $columns[5] = 'total';
        $columns[6] = 'codpessoa';
        $columns[7] = 'codpessoafavorecido';
        $columns[8] = 'codvalecompramodelo';
        $columns[9] = 'criacao';
        $columns[10] = 'codusuariocriacao';

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
                url('vale-compra', $reg->codvalecompra),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codvalecompra),
                $reg->aluno,
                $reg->turma,
                $reg->total,
                $reg->Pessoa->fantasia,
                $reg->PessoaFavorecido->fantasia,
                $reg->ValeCompraModelo->modelo,
                formataData($reg->criacao, 'L'),
                $reg->UsuarioCriacao->usuario,
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
        //$this->repository->new();

        // autoriza
        $this->repository->authorize('create');

        // breadcrumb
        $this->bc->addItem('Novo');
        
        
        if (!empty($request->get('codvalecompramodelo'))) {
            
            $modelo = $this->valeCompraModeloRepository->findOrFail($request->get('codvalecompramodelo'));
            
            $model = $this->repository->new($modelo->getAttributes());
            $model->codpessoa = $this->pessoaRepository->model::CONSUMIDOR;
            $model->codvalecompramodelo = $request->get('codvalecompramodelo');
            
            foreach ($modelo->ValeCompraModeloProdutoBarraS as $m_prod) {
                $prods[] = $this->valeCompraProdutoBarraRepository->new($m_prod->getAttributes());
            }
            
            return view('vale-compra.create', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'prods'=> $prods]);
        }
        
        $modelos = $this->valeCompraModeloRepository->model->whereNull('tblvalecompramodelo.inativo')
            ->join('tblpessoa', 'tblpessoa.codpessoa', '=', 'tblvalecompramodelo.codpessoafavorecido')
            ->orderBy('tblpessoa.fantasia', 'ASC')
            ->orderBy('modelo', 'ASC')
            ->get();
        
        return view('vale-compra.create-seleciona-modelo',['bc'=>$this->bc, 'modelos'=>$modelos]);        
        
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
        
        $model = $this->repository->new($data);
        $model->totalprodutos = array_sum($data['item_total']);
        $model->total = $model->totalprodutos - $model->desconto;
        
/*        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
*/
        
        // Inicia Transação
        DB::beginTransaction();
        
        if ($model->save()) {
            
            foreach ($data['item_codprodutobarra'] as $key => $codprodutobarra) {
                if (empty($codprodutobarra)) {
                    continue;
                }
                
                $prod = $this->valeCompraProdutoBarraRepository->new([
                    'codvalecompra'     => $model->codvalecompra,
                    'codprodutobarra'   => $codprodutobarra,
                    'quantidade'        => $data['item_quantidade'][$key],
                    'preco'             => $data['item_preco'][$key],
                    'total'             => $data['item_total'][$key],
                ]);
                
                $prod->save();
            }
            
            $pag = $this->valeCompraFormaPagamentoRepository->new([
                'codvalecompra'     => $model->codvalecompra,
                'codformapagamento' => $data['codformapagamento'],
                'valorpagamento'    => $model->total,
            ]);
            
            $pag->save();
            
            // Gera Contas a Receber
            if (!$pag->FormaPagamento->avista) {
                $acumulado = 0;
                for ($i=1; $i<=$pag->FormaPagamento->parcelas; $i++) {
                    if ($i == $pag->FormaPagamento->parcelas) {
                        $valor = $pag->valorpagamento - $acumulado;
                    } else {
                        $valor = floor($pag->valorpagamento / $pag->FormaPagamento->parcelas);
                        if ($valor == 0) {
                            $valor = round($pag->valorpagamento / $pag->FormaPagamento->parcelas, 2);
                        }
                    }
                    $vencimento = ($i==1)?$model->criacao->addDays($pag->FormaPagamento->diasentreparcelas):$vencimento->addDays($pag->FormaPagamento->diasentreparcelas);
                    $acumulado += $valor;
                    $numero = str_pad($model->codvalecompra, 8, '0', STR_PAD_LEFT);
                    $numero = "V{$numero}-{$i}/{$pag->FormaPagamento->parcelas}";
                    
                    $titulo = $this->tituloRepository->new();
                    $titulo->numero = $numero;
                    $titulo->codpessoa = $model->codpessoa;
                    $titulo->codfilial = $model->codfilial;
                    $titulo->codvalecompraformapagamento = $pag->codvalecompraformapagamento; //Venda Vale
                    $titulo->debito = $valor;
                    $titulo->codtipotitulo = 240; //Débito Cliente
                    $titulo->codcontacontabil = 82; //Venda Vale
                    $titulo->transacao = $model->criacao;
                    $titulo->sistema = $model->criacao;
                    $titulo->emissao = $model->criacao;
                    $titulo->vencimento = $vencimento;
                    $titulo->vencimentooriginal = $vencimento;
                    $titulo->save();
                }
            }
            
            // Gera Titulo de Credito
            $numero = str_pad($model->codvalecompra, 8, '0', STR_PAD_LEFT);
            $numero = "V{$numero}-CR";

            $titulo = $this->tituloRepository->new();
            $titulo->numero = $numero;
            $titulo->codpessoa = $model->codpessoafavorecido;
            $titulo->codfilial = $model->codfilial;
            $titulo->credito = $model->total;
            $titulo->codtipotitulo = 3; //Vale Compras
            $titulo->codcontacontabil = 83; //Credito Vale
            $titulo->transacao = $model->criacao;
            $titulo->sistema = $model->criacao;
            $titulo->emissao = $model->criacao;
            $titulo->vencimento = $model->criacao->addYear(1);
            $titulo->vencimentooriginal = $titulo->vencimento;
            $titulo->save();
            
            $model->codtitulo = $titulo->codtitulo;
            $model->save();
            
        }
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Vale Compras criado!');
        
        // Commita Transação
        DB::commit();
        
        // redireciona para o view
        return redirect("vale-compra/{$this->repository->model->codvalecompra}?imprimir=true");
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
        $imprimir = ($request->get('imprimir') == 'true')?true:false;
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->turma);
        $this->bc->addItem($this->repository->model->aluno);
        $this->bc->header = $this->repository->model->aluno;
        
        // retorna show
        return view('vale-compra.show', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'imprimir'=>$imprimir]);
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
        $this->bc->addItem($this->repository->model->codvalecompra, url('vale-compra', $this->repository->model->codvalecompra));
        $this->bc->header = $this->repository->model->codvalecompra;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('vale-compra.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Vale Compras alterado!');
        
        // redireciona para view
        return redirect("vale-compra/{$this->repository->model->codvalecompra}"); 
    }
    
    public function imprimir($id, Request $request) 
    {
        // Pega modelo
        $this->repository->findOrFail($id);
        
        //autorizacao
        $this->repository->authorize('view');
        
        // Se inativo retorna 403
        if (!empty($this->repository->model->inativo)) {
            return response()->view('errors.403', ['mensagem' => 'Não é permitido imprimir vale inativado!'], 403);
        }
        
        // Monta Relatorio
        $rel = new EscPrintValeCompra($this->repository->model);
        $rel->prepara();
        
        // Imprime
        if ($request->get('imprimir') == 'true') {
            $rel->imprimir();
        }
        
        // Retorna relatorio em formato HTML
        return $rel->converteHtml();
    }
    
}
