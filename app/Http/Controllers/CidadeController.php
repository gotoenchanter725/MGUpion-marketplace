<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Cidade;

class CidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Cidade();
        return view('cidade.create', compact('model', 'request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Cidade($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->codestado = $request->get('codestado');
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("cidade/$model->codcidade"); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = Cidade::find($id);
        return view('cidade.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Cidade::findOrFail($id);
        return view('cidade.edit',  compact('model'));
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
        $model = Cidade::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("cidade/$model->codcidade"); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $model = Cidade::find($id);
            $model->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return redirect("estado/$model->codestado");
        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }

    public function select2(Request $request)
    {
        // Parametros que o Selec2 envia
        $params = $request->get('params');
        
        // Quantidade de registros por pagina
        $registros_por_pagina = 100;
        
        // Se veio termo de busca
        if(!empty($params['term'])) {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = Cidade::query();
            $qry->join('tblestado', 'tblcidade.codestado', '=', 'tblestado.codestado');
            
            // Condicoes de busca
            foreach (explode(' ', $params['term']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->whereRaw("(tblcidade.cidade ilike '%{$palavra}%')");
                }
            }
            
            //if ($request->get('somenteAtivos') == 'true') {
            //    $qry->ativo();
            //}
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codcidade', 'cidade', 'tblestado.sigla as uf');
            $qry->orderBy('cidade', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codcidade,
                    'cidade' => $item->cidade,
                    'uf' => $item->uf
                ];
            }
            
            // Monta Retorno
            return [
                'results' => $results,
                'params' => $params,
                'pagination' =>  [
                    'more' => ($total > $params['page'] * $registros_por_pagina)?true:false,
                ]
            ];

        } elseif($request->get('id')) {
            
            // Monta Retorno
            $item = Cidade::findOrFail($request->get('id'));
            return [
                'id' => $item->codcidade,
                'cidade' => $item->cidade,
                'uf' => $item->sigla
            ];
        }
    }
}
