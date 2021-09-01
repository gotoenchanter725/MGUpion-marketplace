<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ChequeRepasseRepository;
use \MGLara\Repositories\ChequeRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  ChequeRepasseRepository $repository 
 */
class ChequeRepasseController extends Controller
{

    public function __construct(ChequeRepasseRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Cheque Repasse');
        $this->bc->addItem('Cheque Repasse', url('cheque-repasse'));
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
        return view('cheque-repasse.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codchequerepasse';
        $columns[1] = 'inativo';
        $columns[2] = 'codchequerepasse';
        $columns[3] = 'codchequerepasse';
        $columns[4] = 'codportador';
        $columns[5] = 'data';
        $columns[6] = 'observacoes';

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
                url('cheque-repasse', $reg->codchequerepasse),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codchequerepasse),
                $reg->codchequerepasse,
                $reg->codportador,
                $reg->data,
                $reg->observacoes,
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
        return view('cheque-repasse.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        
        $data = $this->repository->parseData($data);
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        // preenche dados 
        $this->repository->new($data);
        // autoriza
        $this->repository->authorize('create');

        DB::beginTransaction();
            if (!$this->repository->create($data)) {
                abort(500);
            }
        DB::commit();
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Cheque Repasse criado!');
        
        // redireciona para o view
        return redirect("cheque-repasse/{$this->repository->model->codchequerepasse}");
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
        $this->bc->addItem($this->repository->model->codchequerepasse);
        $this->bc->header = $this->repository->model->codchequerepasse;
        // retorna show
        return view('cheque-repasse.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codchequerepasse, url('cheque-repasse', $this->repository->model->codchequerepasse));
        $this->bc->header = $this->repository->model->codchequerepasse;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('cheque-repasse.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Cheque Repasse alterado!');
        
        // redireciona para view
        return redirect("cheque-repasse/{$this->repository->model->codchequerepasse}"); 
    }
    
    public function consulta(Request $request){
        // Pega listagem dos registros
        $this->repository->authorize('listing');

        $columns[0] = 'codcheque';
        $columns[1] = 'inativo';
        $columns[2] = 'codcheque';
        $columns[3] = 'codbanco';
        $columns[4] = 'agencia';
        $columns[5] = 'contacorrente';
        $columns[6] = 'numero';
        $columns[7] = 'codpessoa';
        $columns[8] = 'emitente';
        $columns[9] = 'valor';
        $columns[10] = 'vencimento';
        $columns[11] = 'vencimento';
        $columns[12] = 'indstatus';

        $sort = [];
        if (!empty($request['order'])) {
            foreach ($request['order'] as $order) {
                $sort[] = [
                    'column' => $columns[$order['column']],
                    'dir' => $order['dir'],
                ];
            }
        }
        $filtros = [
            'vencimento_de' => $request['vencimento_de'],
            'vencimento_ate' => $request['vencimento_ate'],
            'indstatus' => 1,
            'inativo' => 1
        ];
        
        $repoCheque = new ChequeRepository();
        $regs = $repoCheque->listing($filtros, $sort, null, 1000);
        
        $recordsTotal = $regs['recordsTotal'];
        $recordsFiltered = $regs['recordsFiltered'];
        
         // Formata registros para exibir
        $data = [];
        foreach ($regs['data'] as $reg) {
            $emits = [];
            
            foreach($reg->ChequeEmitenteS as $emit){
                $emits[] = [
                'cnpj'=>formataCpfCnpj($emit->cnpj),
                'emitente'=>$emit->emitente
                ];
            }
            
            $status = $repoCheque->status($reg->indstatus);
            $data[] = [
                'urlcheque' => url('cheque', $reg->codcheque),
                'codcheque' => $reg->codcheque,
                'codchequerepassecheque' => '',
                'banco' => $reg->Banco->banco,
                'agencia' => $reg->agencia,
                'contacorrente' => $reg->contacorrente,
                'numero' =>  $reg->numero,
                'pessoa' => $reg->Pessoa->pessoa,
                'emitentes' => $emits,
                'valor_formatado'=>formataNumero($reg->valor, 2),
                'valor'=>$reg->valor,
                'emissao' => formataData($reg->emissao),
                'vencimento' => formataData($reg->vencimento),
                'status' => '<span class="'.$status['label'].'">'.$status['status'].'</span>',
            ];
        }
        
        return ['data'=>$data,'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];
    }
   
   
}
