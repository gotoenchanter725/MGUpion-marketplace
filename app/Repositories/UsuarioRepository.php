<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\Usuario;

/**
 * Description of UsuarioRepository
 * 
 * @property Validator $validator
 * @property Usuario $model
 */
class UsuarioRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Usuario();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->model->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codusuario;
        }
        
        $this->validator = Validator::make($data, [
            'usuario' => ['required','min:2', Rule::unique('tblusuario')->ignore($id, 'codusuario')],  
            //'senha' => 'required_if:codusuario,null|min:6', 
            'impressoramatricial' => 'required', 
            'impressoratermica' => 'required',  
        ], [
            'usuario.required' => 'O campo usuário não pode ser vazio!',
            'usuario.unique' => 'Este usuário já esta cadastrado!',
            'impressoramatricial.required' => 'Selecione uma impressora térmica!',
            'impressoratermica.required' => 'Selecione uma impressora matricial!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if ($this->model->NegocioS->count() > 0) {
            return 'Usuário sendo utilizada em Negócios!';
        }
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = Usuario::query();
        $qry->select([
            'tblusuario.codusuario',
            'tblusuario.inativo', 
            'tblusuario.usuario', 
            'tblpessoa.pessoa', 
            'tblfilial.filial']);
        $qry->leftJoin('tblpessoa', 'tblpessoa.codpessoa', '=', 'tblusuario.codpessoa');
        $qry->leftJoin('tblfilial', 'tblfilial.codfilial', '=', 'tblusuario.codfilial');

        // Filtros
        if (!empty($filters['codusuario'])) {
            $qry->where('tblusuario.codusuario', '=', $filters['codusuario']);
        }
        
        if (!empty($filters['usuario'])) {
            $qry->palavras('usuario', $filters['usuario']);
        }        
        
        if (!empty($filters['codfilial'])) {
            $qry->where('tblusuario.codfilial', '=', $filters['codfilial']);
        }
        
        if (!empty($filters['codpessoa'])) {
            $qry->where('tblusuario.codpessoa', '=', $filters['codpessoa']);
        }
        
        switch ($filters['inativo']) {
            case 2: //Inativos
                $qry = $qry->inativo();
                break;

            case 9: //Todos
                break;

            case 1: //Ativos
            default:
                $qry = $qry->ativo();
                break;
        }
        
        $count = $qry->count();

        // Paginacao
        if (!empty($start)) {
            $qry->offset($start);
        }
        if (!empty($length)) {
            $qry->limit($length);
        }
        
        // Ordenacao
        foreach ($sort as $s) {
            $qry->orderBy($s['column'], $s['dir']);
        }
        
        // Registros
        return [
            'recordsFiltered' => $count
            , 'recordsTotal' => Usuario::count()
            , 'data' => $qry->get()
        ];
    }
    
}
