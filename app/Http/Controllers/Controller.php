<?php

namespace MGLara\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use MGLara\Library\JsonEnvelope\Resultado;

use Carbon\Carbon;

/**
 * @property Breadcrumb $bc Breadcrumb
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Monta a chave da sessao para armazenar o filtro
     * 
     * @param string $sufixo Sufixo a ser utilizado na chave da sessao
     * @param string $chave Chave da sessao a ser utilizada
     * @return string
     */
    public function montaChaveFiltro ($sufixo = null, $chave = null) {
        $chave = $chave??str_replace('\\', ".", get_class($this));
        if (!empty($sufixo)) {
            $chave .= ".$sufixo";
        }
        return $chave;
    }

    /**
     * Armazena filtro de busca na sessao
     * 
     * @param array $filtros Array com os filtros para gravar na sessao
     * @param string $sufixo Sufixo a ser utilizado na chave da sessao
     * @param string $chave Chave da sessao a ser utilizada
     * @return array
     */
    public function setFiltro($filtro, $sufixo = null, $chave = null) {
        $chave = $this->montaChaveFiltro($sufixo, $chave);
        return session([$chave => $filtro]);
    }
    
    /**
     * Recupera filtro de busca armazenado na sessao
     * 
     * @param string $sufixo Sufixo a ser utilizado na chave da sessao
     * @param string $chave Chave da sessao a ser utilizada
     * @return array
     */
    public function getFiltro($sufixo = null, $chave = null) {
        $chave = $this->montaChaveFiltro($sufixo, $chave);
        return session($chave);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Busca o registro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('delete');
        
        // se esta sendo usado
        if ($mensagem = $this->repository->used()) {
            return ['OK' => false, 'mensagem' => $mensagem];
        }
        
        // apaga
        return ['OK' => $this->repository->delete()];
    }

    /**
     * Ativa um registro
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id) {
        
        // Busca o registro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // ativa
        return ['OK' => $this->repository->activate()];
        
    }
    
    /**
     * Inativa um registro
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id) {
        
        // Busca o registro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // ativa
        return ['OK' => $this->repository->inactivate()];
        
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
        // Busca registro para autorizar
        $this->repository->findOrFail($id);

        // Valida dados
        $data = $request->all();
        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        // autorizacao
        $this->repository->fill($data);
        $this->repository->authorize('update');
        
        // salva
        if (!$this->repository->update()) {
            abort(500);
        }
        
    } 
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // busca dados do formulario
        $data = $request->all();
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        // cria
        if (!$this->repository->create()) {
            abort(500);
        }
    }    
}
