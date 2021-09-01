<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Controllers\Controller;
use MGLara\Repositories\MetaRepository;
use MGLara\Repositories\MetaFilialRepository;
use MGLara\Repositories\MetaFilialPessoaRepository;
use MGLara\Repositories\FilialRepository;

use Illuminate\Support\Facades\Session;
use MGLara\Library\Breadcrumb\Breadcrumb;
use DB;
use Carbon\Carbon;

/**
 * @property  MetaRepository $repository 
 * @property  MetaFilialRepository $metaFilialRepository 
 * @property  MetaFilialPessoaRepository $metaFilialPessoaRepository
 * @property  FilialRepository $filialRepository
 */

class MetaController extends Controller
{
    public function __construct(MetaRepository $repository, MetaFilialRepository $metaFilialRepository, MetaFilialPessoaRepository $metaFilialPessoaRepository, FilialRepository $filialRepository) {
        $this->repository = $repository;
        $this->metaFilialRepository = $metaFilialRepository;
        $this->metaFilialPessoaRepository = $metaFilialPessoaRepository;
        $this->filialRepository = $filialRepository;
        $this->bc = new Breadcrumb('Meta');
        $this->bc->addItem('Meta', url('meta'));
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Permissao
        $this->repository->authorize('listing');

        // Breadcrumb
        $this->bc->addItem('Listagem');

        $model = $this->repository->model->where('periodoinicial', '<=', Carbon::today())
                ->where('periodofinal', '>=', Carbon::today())
                ->first();
        
        if($model) {
            return redirect("meta/$model->codmeta");
        } else {
            $model = $this->repository->model->orderBy('periodofinal', 'DESC')->first();
            if(!is_null($model)) {
                return redirect("meta/$model->codmeta");
            } else {
                return view('meta.index', compact('model'));
            }
        }
        
        // retorna View
        return view('meta.index', ['bc'=>$this->bc, 'model'=>$model]);        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // busca registro
        $this->repository->findOrFail($id);
        $dados = $this->repository->totalVendas();
        
        if ($request->get('debug') == true) {
            return $dados;
        }        
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem(formataData($this->repository->model->periodofinal, 'EC'));
        $this->bc->header = formataData($this->repository->model->periodofinal, 'EC');
        
        $proximos = $this->repository->buscaProximos(8);
        $anteriores = $this->repository->buscaAnteriores(16 - sizeof($proximos));
        
        if (sizeof($anteriores) < 8) {
            $proximos = $this->repository->buscaProximos(16 - sizeof($anteriores));
        }        
        
        // retorna show
        return view('meta.show', [
            'bc' => $this->bc, 
            'model' => $this->repository->model, 
            'dados' => $dados, 
            'proximos' => $proximos, 
            'anteriores'=> $anteriores
        ]);
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
        return view('meta.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all()['meta'];
        $data['periodoinicial'] = new Carbon($data['periodoinicial']);
        $data['periodofinal'] = new Carbon($data['periodofinal']);
        $this->repository->new($data);
        $this->repository->authorize('create');
        
        DB::beginTransaction();
        
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        try {
            if (!$this->repository->create()) {
                 abort(500);   
            }
            
            $metasfilial = $request->all()['metafilial'];
            foreach ($metasfilial as $metafilial => $meta)
            {
                if(!empty($meta['controla'])) {

                    $mf = $this->metaFilialRepository->new();
                    $mf->codfilial = $metafilial;
                    $mf->codmeta = $this->repository->model->codmeta;
                    $mf->valormetafilial    = $meta['valormetafilial'];
                    $mf->valormetavendedor  = $meta['valormetavendedor'];
                    $mf->observacoes        = $meta['observacoes'];
                    
                    if (!$mf->save()) {
                        throw new Exception ('Erro ao Criar Meta Filial!');
                    }
                    
                    $pessoas = $meta['pessoas'];
                    foreach ($pessoas as $pessoa)
                    {
                        //$mfp = new MetaFilialPessoa();
                        $mfp = $this->metaFilialPessoaRepository->new();
                        $mfp->codmetafilial = $mf->codmetafilial;
                        $mfp->codpessoa     = $pessoa['codpessoa'];
                        $mfp->codcargo      = $pessoa['codcargo'];
                        
                        if($mfp->codcargo && $mfp->codpessoa) {
                            if (!$mfp->save()) {
                                throw new Exception ('Erro ao Criar Meta filial pessoa!');
                            }
                        }
                    }
                }
            }
            
            DB::commit();
            Session::flash('flash_create', 'Meta criada!');
            return redirect("meta/{$this->repository->model->codmeta}");            
            
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $this->repository->validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // busca regstro
        $model = $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        $model['meta'] =  $model->getAttributes();
        $metasfiliais = [];
        foreach($model->MetaFilialS()->get() as $metafilial)
        {
            $metasfiliais[$metafilial['codfilial']] = $metafilial;
            $metafilial['controla'] = TRUE;
            $pessoas = [];
            foreach ($metafilial->MetaFilialPessoaS()->get() as $pessoa)
            {
                $pessoas[$pessoa['codpessoa']] = [
                    'codmetafilialpessoa' => $pessoa['codmetafilialpessoa'],
                    'codpessoa'=> $pessoa['codpessoa'],
                    'codcargo'=> $pessoa['codcargo']
                ];
            }
            $metasfiliais[$metafilial['codfilial']]['pessoas'] = $pessoas;
        }
        $model['metafilial'] = $metasfiliais;
        
        // breadcrumb
        $this->bc->addItem(formataData($this->repository->model->periodofinal, 'EC'), url('meta', $this->repository->model->codmeta));
        $this->bc->header = 'Meta - ' . formataData($this->repository->model->periodofinal, 'EC');
        $this->bc->addItem('Alterar');        
        
        return view('meta.edit',  ['model'=> $model, 'bc'=>  $this->bc]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->repository->findOrFail($id);
        $data = $request->all()['meta'];
        $data['periodoinicial'] = new Carbon($data['periodoinicial']);
        $data['periodofinal'] = new Carbon($data['periodofinal']);
        
        $this->repository->fill($data);
        $this->repository->authorize('update');
        
        DB::beginTransaction();

        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        try {
            if (!$this->repository->update()){
                throw new Exception ('Erro ao Alterar Meta!');            
            }
            
            $metasfilial = $request->all()['metafilial'];
            foreach ($metasfilial as $metafilial => $meta)
            {
                if(isset($meta['controla'])) {
                    
                    if(empty($meta['codmetafilial'])) {
                        //$mf = new MetaFilial();
                        $mf = $this->metaFilialRepository->new();
                        $mf->codfilial = $metafilial;
                        $mf->codmeta = $model->codmeta;
                    } else {
                        $mf = $this->metaFilialRepository->findOrFail($meta['codmetafilial']);
                    }
                    $mf->valormetafilial    = $meta['valormetafilial'];
                    $mf->valormetavendedor  = $meta['valormetavendedor'];
                    $mf->observacoes        = $meta['observacoes'];
                    
                    if (!$mf->update()) {
                        throw new Exception ('Erro ao Alterar Meta Filial!');
                    }

                    if(isset($meta['pessoas'])) {
                        $codmetafilialpessoa = [];
                        foreach ($meta['pessoas'] as $pessoa_dado)
                        {
                            $pessoa_dados = [
                                'codmetafilial' => (int) $mf->codmetafilial,
                                'codpessoa'     => (int) $pessoa_dado['codpessoa'],
                                'codcargo'      => (int) $pessoa_dado['codcargo']
                            ];

                            if(!empty($pessoa_dado['codmetafilialpessoa'])) {
                                $pessoa = $this->metaFilialPessoaRepository->findOrFail($pessoa_dado['codmetafilialpessoa']);
                                $pessoa->fill($pessoa_dados);
                            } else {
                                $pessoa = $this->metaFilialPessoaRepository->new();
                                $pessoa->fill($pessoa_dados);
                            }
                            /*
                            if (!$this->metaFilialPessoaRepository->validate($pessoa, $id)) {
                                $this->throwValidationException($request, $pessoa->validator);
                            }
                            */
                            $pessoa->save();
                            
                            $codmetafilialpessoa[] = $pessoa->codmetafilialpessoa;
                        }
                        $mf->MetaFilialPessoaS()->whereNotIn('codmetafilialpessoa', $codmetafilialpessoa)->delete();
                    } else {
                        $mf->MetaFilialPessoaS()->delete();
                    }
                }
            }
            
            DB::commit();
            Session::flash('flash_update', 'Registro alterado!');
            return redirect("meta/{$this->repository->model->codmeta}"); 
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $this->repository->validator);
        }        
    }
    
    
    public function destroy($id)
    {
        $meta = $this->repository->findOrFail($id);
        $this->repository->authorize('delete');
        foreach ($meta->MetaFilialS()->get() as $mf)
        {
            foreach ($mf->MetaFilialPessoaS()->get() as $mfp)
            {
                if (!$mfp->delete()){
                    return ['OK' => false, 'mensagem' => 'Erro excluir Meta Filial Pessoa!!'];
                }                    
            }
            if (!$mf->delete()){
                return ['OK' => false, 'mensagem' => 'Erro excluir Meta Filial!'];
            }                    
        }
        
        // apaga
        return ['OK' => $this->repository->delete()];
    }

}
