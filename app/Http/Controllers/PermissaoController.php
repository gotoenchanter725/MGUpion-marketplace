<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\PermissaoRepository;

use MGLara\Models\Permissao;
use MGLara\Models\GrupoUsuario;
use MGLara\Models\GrupoUsuarioPermissao;

use MGLara\Library\Breadcrumb\Breadcrumb;

use Carbon\Carbon;

class PermissaoController extends Controller
{

    public function __construct(PermissaoRepository $repository) {
        $this->bc = new Breadcrumb('Permissões');
        $this->bc->addItem('Permissões', url('permissao'));
        $this->repository = $repository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        // Permissao
        $this->repository->authorize('listing');
        
        // Pega todos arquivos da pasta de Policies
        $arquivos = scandir(base_path() . '/app/Policies/');
        
        // Percorre arquivos para buscar metodos
        $classes = [];
        foreach ($arquivos as $arquivo) {
            
            // Ignora arquivos
            if (in_array($arquivo, ['.', '..', 'MGPolicy.php'])) {
                continue;
            }
            
            if (strstr($arquivo, '.old.')) {
                continue;
            }
            
            // Tira a extensao do arquivo
            $classe = str_replace('.php', '', $arquivo);
            
            // Pega metodos da Classe
            $metodos = get_class_methods("MGLara\\Policies\\{$classe}");
            
            // Metodos para serem ignorados
            $metodos = array_diff($metodos, ['before']);
            
            $metodos_como_chave = [];
            foreach ($metodos as $metodo) {
                $metodos_como_chave[$metodo] = 0;
            }
            
            // Acumula na listagem de classes
            $classes[$classe] = $metodos_como_chave;
        }
        
        // Percorre Permissoes combinando com as classes
        foreach (Permissao::orderBy('permissao')->get() as $permissao) {
            
            $classe = null;
            $metodo = null;
            
            // Tenta separar do formato 'Classe.metodo'
            if ($ponto = strpos($permissao->permissao, '.')) {
                $classe = substr($permissao->permissao, 0, $ponto);
                $metodo = substr($permissao->permissao, $ponto + 1);
            }
            
            // Se existe uma permissao no banco, associa o codigo
            if (isset($classes[$classe][$metodo])) {
                $classes[$classe][$metodo] = $permissao->codpermissao;
                
            // Senao Lista como obsoleta
            } else {
                $classes['OBSOLETAS'][$permissao->permissao] = $permissao->codpermissao;
            }
            
        }

        // Listagem dos Grupos
        $grupos = GrupoUsuario::ativo()->orderBy('grupousuario')->get();
        
        // Listagem das Permissoes dos Grupos
        $grupopermissoes = GrupoUsuarioPermissao::select('codgrupousuariopermissao', 'codgrupousuario', 'codpermissao')->get();
        $permissoes = [];
        foreach ($grupopermissoes as $grupopermissao) {
            $permissoes[$grupopermissao->codgrupousuario][$grupopermissao->codpermissao] = $grupopermissao->codgrupousuariopermissao;
        }
        
        return view('permissao.index', ['bc'=>$this->bc, 'classes'=>$classes, 'grupos'=>$grupos, 'permissoes'=>$permissoes]);
        
    }
    
    public function store(Request $request)
    {
        // Permissao
        $this->repository->authorize('create');
        
        // Monta a chave da permissao
        if ($request->classe == 'OBSOLETAS') {
            $chave = $request->metodo;
        } else {
            $chave = "{$request->classe}.{$request->metodo}";
        }
        
        // Se nao Existe Permissao, cria
        if (!$permissao = Permissao::where('permissao', $chave)->first()) {
            $permissao = Permissao::create(['permissao' => $chave]);
        }
        
        // Associa a permissao com o grupo de usuario
        if (!$grupo_permissao = GrupoUsuarioPermissao::where('codgrupousuario', $request->codgrupousuario)->where('codpermissao', $permissao->codpermissao)->first()) {
            $grupo_permissao = GrupoUsuarioPermissao::create(['codgrupousuario' => $request->codgrupousuario, 'codpermissao'=>$permissao->codpermissao]);
        }
        
        //retorna
        return [
            'OK' => $grupo_permissao->codgrupousuariopermissao,
            'grupousuario' => $grupo_permissao->GrupoUsuario->grupousuario,
            'permissao' => $chave,
        ];
    }
    
    public function destroyPermissao(Request $request)
    {
        
        // Permissao
        $this->repository->authorize('delete');
        
        // monta a chave da permissao
        if ($request->classe == 'OBSOLETAS') {
            $chave = $request->metodo;
        } else {
            $chave = "{$request->classe}.{$request->metodo}";
        }
        
        // Se nao existe a permissao, aborta
        if (!$permissao = Permissao::where('permissao', $chave)->first()) {
            abort(404);
        }
        
        // Exclui registros
        $excluidos = GrupoUsuarioPermissao::where('codgrupousuario', $request->codgrupousuario)->where('codpermissao', $permissao->codpermissao)->delete();
        
        //retorna
        return [
            'OK' => $excluidos,
            'grupousuario' => GrupoUsuario::findOrFail($request->codgrupousuario)->grupousuario,
            'permissao' => $chave,
        ];
        
    }
    
}
