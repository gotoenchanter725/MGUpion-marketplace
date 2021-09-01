<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\CobrancaHistorico;
use MGLara\Models\Pessoa;
use MGLara\Models\RegistroSpc;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function index(Request $request) {
        return redirect()->away("/MGsis/index.php?r=pessoa/index");        
        $parametros = self::filtroEstatico($request, 'pessoa.index', ['ativo' => 1]);
        $model = Pessoa::search($parametros)->orderBy('fantasia', 'ASC')->paginate(20);
        return view('pessoa.index', compact('model'));
    }
     * 
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function create()
    {
        $model = new Pessoa();
        return view('pessoa.create', compact('model'));
    }
     * 
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*
    public function store(Request $request)
    {
        $model = new Pessoa($request->all());

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("pessoa/$model->codpessoa");
    }
     * 
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function show(Request $request, $id)
    {
        return redirect()->away("/MGsis/index.php?r=pessoa/view&id=$id");        
        $model     = Pessoa::find($id);
        $cobrancas = CobrancaHistorico::byPessoa($id)->paginate(10);
        $spcs      = RegistroSpc::byPessoa($id)->paginate(10);
        return view('pessoa.show', compact('model', 'estados', 'cobrancas', 'spcs'));
    }
     * 
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function edit($id)
    {
        $model = Pessoa::findOrFail($id);
        return view('pessoa.edit', compact('model'));
    }
     * 
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function update(Request $request, $id)
    {
        $model = Pessoa::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        if ($request->input('cliente') == 1) {
            $model->cliente = true;
        } else {
            $model->cliente = false;
        }

        if ($request->input('fornecedor') == 1) {
            $model->fornecedor = true;
        } else {
            $model->fornecedor = false;
        }

        if ($request->input('fisica') == 1) {
            $model->fisica = true;
        } else {
            $model->fisica = false;
        }

        if ($request->input('consumidor') == 1) {
            $model->consumidor = true;
        } else {
            $model->consumidor = false;
        }

        if ($request->input('creditobloqueado') == 1) {
            $model->creditobloqueado = true;
        } else {
            $model->creditobloqueado = false;
        }

        if ($request->input('vendedor') == 1) {
            $model->vendedor = true;
        } else {
            $model->vendedor = false;
        }

        $model->save();

        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("pessoa/$model->codpessoa");
    }
     * 
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function destroy($id)
    {
        try {
            Pessoa::find($id)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return Redirect::route('pessoa.index');
        } catch (\Exception $e) {
            return view('errors.fk');
        }
    }
     * 
     */

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
            $qry = Pessoa::query();
            
            // Condicoes de busca
            foreach (explode(' ', $params['term']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->whereRaw("(tblpessoa.pessoa ilike '%{$palavra}%' or tblpessoa.fantasia ilike '%{$palavra}%')");
                }
            }
            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codpessoa', 'pessoa', 'fantasia', 'cnpj', 'inativo', 'fisica');
            $qry->orderBy('fantasia', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codpessoa,
                    'codpessoa' => formataCodigo($item->codpessoa),
                    'pessoa' => $item->pessoa,
                    'fantasia' => $item->fantasia,
                    'cnpj' => formataCnpjCpf($item->cnpj, $item->fisica),
                    'inativo' => formataData($item->inativo, 'C'),
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
            $item = Pessoa::findOrFail($request->get('id'));
            return [
                'id' => $item->codpessoa,
                'codpessoa' => formataCodigo($item->codpessoa),
                'pessoa' => $item->pessoa,
                'fantasia' => $item->fantasia,
                'cnpj' => formataCnpjCpf($item->cnpj, $item->fisica),
                'inativo' => formataData($item->inativo, 'C'),
            ];
            
        }

    }
}
