<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use MGLara\Repositories\UsuarioRepository;
use MGLara\Repositories\GrupoUsuarioRepository;
use MGLara\Repositories\FilialRepository;
use MGLara\Repositories\GrupoUsuarioUsuarioRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;


class UsuarioController extends Controller
{
    public function __construct(UsuarioRepository $repository, GrupoUsuarioRepository $grupoUsuarioRepository, FilialRepository $filialRepository, GrupoUsuarioUsuarioRepository $grupoUsuarioUsuarioRepository) {
        $this->repository = $repository;
        $this->grupoUsuarioRepository = $grupoUsuarioRepository;
        $this->filialRepository = $filialRepository;
        $this->grupoUsuarioUsuarioRepository = $grupoUsuarioUsuarioRepository;
        $this->bc = new Breadcrumb('Usuários');
        $this->bc->addItem('Usuários', url('usuario'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
                    'column' => 3, 
                    'dir' => 'ASC'
                ]],
            ];
        }
        
        // retorna View
        return view('usuario.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }
    
    public function datatable(Request $request) {
        
        // Autorizacao
        $this->repository->authorize('listing');

        // Grava Filtro para montar o formulario da proxima vez que o index for carregado
        $this->setFiltro([
            'filtros' => $request['filtros'],
            'order' => $request['order'],
        ]);
        
        // Ordenacao
        $columns[0] = 'tblusuario.codusuario';
        $columns[1] = 'tblusuario.inativo';
        $columns[2] = 'tblusuario.codusuario';
        $columns[3] = 'tblusuario.usuario';
        $columns[4] = 'tblpessoa.pessoa';
        $columns[5] = 'tblfilial.filial';
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
                url('usuario', $reg->codusuario),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codusuario),
                $reg->usuario,
                $reg->pessoa,
                $reg->filial,
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
     * @return \Illuminate\Http\Response
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
        return view('usuario.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parent::store($request);
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Usuário criado!');
        
        // redireciona para o view
        return redirect("usuario/{$this->repository->model->codusuario}");
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
        
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->usuario);
        $this->bc->header = $this->repository->model->usuario;
        
        // retorna show
        return view('usuario.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->usuario, url('usuario', $this->repository->model->codusuario));
        $this->bc->header = $this->repository->model->usuario;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('usuario.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        parent::update($request, $id);
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Registro alterado!');
        
        // redireciona para view
        return redirect("usuario/{$this->repository->model->codusuario}"); 
    }
    
    public function grupos(Request $request, $codusuario) {
        
        $this->repository->findOrFail($codusuario);
        
        $this->bc->addItem($this->repository->model->usuario, url('usuario', $this->repository->model->codusuario));
        $this->bc->addItem('Grupos');        
        $this->bc->header = $this->repository->model->usuario;
        
        $grupos_usuario = [];
        foreach ($this->repository->model->GrupoUsuarioUsuarioS as $guu) {
            $grupos_usuario[$guu->codgrupousuario][$guu->codfilial] = $guu->codgrupousuariousuario;
        }
        $filiais = $this->filialRepository->model->orderBy('filial')->ativo()->get();
        $grupos = $this->grupoUsuarioRepository->model->orderBy('grupousuario')->ativo()->get();
        
        return view('usuario.grupos', ['bc' => $this->bc, 'model' => $this->repository->model, 'grupos' => $grupos, 'filiais' => $filiais, 'grupos_usuario' => $grupos_usuario]);
    }
    
    public function gruposCreate(Request $request, $id) {
        
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('gruposCreate');
        
        // Associa a permissao com o grupo de usuario
        if (!$grupo_usuario = $this->repository->model->GrupoUsuarioUsuarioS()->where('codgrupousuario', $request->codgrupousuario)->where('codfilial', $request->codfilial)->first()) {
            $grupo_usuario = $this->grupoUsuarioUsuarioRepository->model->create(['codusuario' => $id, 'codgrupousuario' => $request->codgrupousuario, 'codfilial'=>$request->codfilial]);
        }
        
        //retorna
        return [
            'OK' => $grupo_usuario->codgrupousuariousuario,
            'grupousuario' => $grupo_usuario->GrupoUsuario->grupousuario,
            'filial' => $grupo_usuario->Filial->filial,
        ];
        
    }
    
    public function gruposDestroy(Request $request, $id) {
        
        $usuario = $this->repository->findOrFail($id);

        // autorizacao
        $this->repository->authorize('gruposDestroy');
        
        // Exclui registros
        $excluidos = $usuario->GrupoUsuarioUsuarioS()->where('codgrupousuario', $request->codgrupousuario)->where('codfilial', $request->codfilial)->delete();
        
        //retorna
        return [
            'OK' => $excluidos,
            'grupousuario' => $this->grupoUsuarioRepository->model->findOrFail($request->codgrupousuario)->grupousuario,
            'filial' => $this->filialRepository->model->findOrFail($request->codfilial)->filial,
        ];
        
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mudarSenha(Request $request)
    {
        // busca registro
        $this->repository->findOrFail(Auth::user()->codusuario);
        
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->usuario);
        $this->bc->header = $this->repository->model->usuario;
        
        // retorna show
        return view('usuario.mudar-senha', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }
    
    public function mudarSenhaUpdate(Request $request)
    {
        // Busca registro para autorizar
        $this->repository->findOrFail(Auth::user()->codusuario);
        $data = $request->all();
        
        Validator::extend('igual', function ($attribute, $value, $parameters) {
            if ($value == $parameters[0]) {
                return true;
            } else {
                return false;
            }
        });         
        
        Validator::extend('senhaAntiga', function ($attribute, $value, $parameters) {
            if (password_verify($value, $this->repository->model->senha)) {
                return true;
            } else {
                return false;
            }
        });         
        
        $mensagens = [
            'senha.igual' => 'A confirmação de senha é diferente da senha',
            'senhaantiga.senha_antiga' => 'Senha antiga incorreta'
        ];
        
        // Valida dados
        Validator::make($data, [
            'senhaantiga' => 'required|senhaAntiga',
            'senha' => 'required|igual:'. $data['repetir_senha']
        ], $mensagens)->validate();        
        
        $data['senha'] = bcrypt($data['senha']);

        // autorizacao
        $this->repository->fill($data);
        $this->repository->authorize('update');
        
        // salva
        if (!$this->repository->update()) {
            abort(500);
        }        
        
        Session::flash('flash_update', 'Senha alterada!');

        // redireciona para view
        return redirect("usuario/{$this->repository->model->codusuario}"); 
    }
}
