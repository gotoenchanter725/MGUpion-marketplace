<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use DB;
use MGLara\Repositories\ChequeRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

use MGLara\Repositories\BancoRepository;
use MGLara\Repositories\PessoaRepository;
use MGLara\Library\Cmc7\Cmc7;
use MGLara\Repositories\ChequeEmitenteRepository;
use MGLara\Repositories\ChequeRepasseRepository;

/**
 * @property  ChequeRepository $repository 
 */

class ChequeController extends Controller
{
    //
    public function __construct(ChequeRepository $repository) {
        $this->repository = $repository;

        
        $this->bc = new Breadcrumb('Cheque');
        $this->bc->addItem('Cheque', url('cheque'));
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
                    'inativo' => 1
                ],
                'order' => [[
                    'column' => 0,
                    'dir' => 'DESC',
                ]],
            ];
        }
      
        $status = $this->repository->status_select2();
        
        // retorna View
        return view('cheque.index', ['bc'=>$this->bc, 'filtro'=>$filtro,'status'=>$status]);
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
        //'Banco', 'Agencia', 'Contacorrente', 'Numero', 'Pessoa', 'Emitentes', 'Valor', 'Data Emissão', 'Data Vencimento', 'Status'
        // Ordenacao
        $columns[0] = 'codcheque';
        $columns[1] = 'inativo';
        $columns[2] = 'selecao';
        $columns[3] = 'codcheque';
        $columns[4] = 'codbanco';
        $columns[5] = 'agencia';
        $columns[6] = 'contacorrente';
        $columns[7] = 'numero';
        $columns[8] = 'codpessoa';
        $columns[9] = 'emitente';
        $columns[10] = 'valor';
        $columns[11] = 'vencimento';
        $columns[12] = 'vencimento';
        $columns[13] = 'indstatus';

        
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
            $status = $this->repository->status($reg->indstatus);
            
            $emits = '';
            foreach($reg->ChequeEmitenteS as $emit){
                $emits .= '&bull; '.$emit->emitente.' <br>';
            }
            
            $data[] = [
                url('cheque', $reg->codcheque),
                formataData($reg->inativo, 'C'),
                '<input type="checkbox" name="chequeseleciona[]" data-valor="'.$reg->valor.'" onclick="chequeseleciona()" value="'.$reg->codcheque.'">',
                formataCodigo($reg->codcheque),
                $reg->Banco->banco,
                $reg->agencia,
                $reg->contacorrente,
                $reg->numero,
                $reg->Pessoa->pessoa,
                $emits,
                formataNumero($reg->valor, 2),
                formataData($reg->emissao),
                formataData($reg->vencimento),
                '<span class="'.$status['label'].'">'.$status['status'].'</span>',
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
        return view('cheque.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        
        // cria
        if (!$this->repository->create($data)) {
            abort(500);
        }
       
        DB::commit();
       
        // Mensagem de registro criado
        Session::flash('flash_create', 'Cheque criado!');
        
        // redireciona para o view
        return redirect("cheque/{$this->repository->model->codcheque}");
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
        $this->bc->addItem($this->repository->model->cmc7);
        $this->bc->header = $this->repository->model->cmc7;
        $status = $this->repository->status($this->repository->model['indstatus']);
        // retorna show
        
        return view('cheque.show', ['bc'=>$this->bc, 'model'=>$this->repository->model,'status'=>$status]);
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
        $this->bc->addItem($this->repository->model->cmc7, url('cheque', $this->repository->model->codcheque));
        $this->bc->header = $this->repository->model->cmc7;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('cheque.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
  
        // busca dados do formulario
        $data = $request->all();
      
        $data = $this->repository->parseData($data);
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        // autoriza
        $this->repository->authorize('create');
        
        DB::beginTransaction();
        
        // atualiza dados
        if (!$this->repository->update($id, $data)) {
            abort(500);
        }
       
        DB::commit();
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Cheque alterado!');
        
        // redireciona para view
        return redirect("cheque/{$this->repository->model->codcheque}"); 
    }
    
    public function destroy($id) {
        
        // autorizacao
        $this->repository->authorize('delete');
        
        DB::beginTransaction();
            $retorno = $this->repository->delete($id);
        DB::commit();
        
        return ['OK' => $retorno];   
    }
    
    public function consulta($cmc7) {
        
        // Autorizacao
        $this->repository->authorize('listing');
        
        $ret = $this->repository->consultaCmc7($cmc7);

        return $ret;
    }
    
    public function consultaemitente($cnpj) {
        
        $ret = $this->repository->consultaEmitente($cnpj);
        return $ret;
        
    }
}
