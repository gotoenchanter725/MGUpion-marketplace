<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use MGLara\Http\Controllers\Controller;
use MGLara\Library\Breadcrumb\Breadcrumb;

class CaixaController extends Controller
{
    public function __construct() {
        $this->bc = new Breadcrumb('Caixa');
        $this->bc->addItem('Totais de Caixa', url('caixa'));
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inicialpadrao = Carbon::today();
        $finalpadrao = Carbon::today();
        $finalpadrao->hour = 23;
        $finalpadrao->minute = 59;
        $finalpadrao->second = 59;
        $key = str_replace('\\', ".", get_class($this));
        
        if (!$request->session()->has($key)) {
            
            $filtros = [
                'ativo' => 1,
                'codusuario' => Auth::user()->codusuario,
                'datainicial' => $inicialpadrao->format('Y-m-d\TH:i:s'),
                'datafinal' => $finalpadrao->format('Y-m-d\TH:i:s'),
            ];
            
            $request->session()->put($key, $filtros);
        } 
        
        if(!empty($request->all())){
            $request->session()->put($key, $request->all());
        }
        
        $filtro = $request->session()->get($key);
        
        if (empty($filtro['codusuario'])) {
            abort(500, 'Usuário não informado!');
        }

        if (empty($filtro['datainicial'])) {
            abort(500, 'Data Inicial não informada!');
        }

        if (empty($filtro['datafinal'])) {
            abort(500, 'Data Final não informada!');
        }

        switch ($filtro['ativo']) {
            case 1:
                $ativo = 'and n.codnegociostatus = 2';
                break;

            case 2:
                $ativo = 'and n.codnegociostatus != 2';
                break;

            default:
                $ativo = '';
                break;
        }

        $sql = "
            select
              o.operacao
            , n.codoperacao
            , no.naturezaoperacao
            , n.codnaturezaoperacao
            , ns.negociostatus
            , n.codnegociostatus
            , sum(coalesce(valoravista, 0)) as avista
            , sum(coalesce(valoraprazo, 0)) as aprazo
            , sum(coalesce(valortotal, 0)) as total
            , count(n.codnegocio) as quantidade
            from tblnegocio n
            left join tblnegociostatus ns on (ns.codnegociostatus = n.codnegociostatus)
            left join tblnaturezaoperacao no on (no.codnaturezaoperacao = n.codnaturezaoperacao)
            left join tbloperacao o on (o.codoperacao = n.codoperacao)
            where n.codusuario  = {$filtro['codusuario']}
            and n.lancamento between '{$filtro['datainicial']}' and '{$filtro['datafinal']}'
            $ativo
            group by
              ns.negociostatus
            , n.codnegociostatus
            , o.operacao
            , n.codoperacao
            , no.naturezaoperacao
            , n.codnaturezaoperacao
            order by
              ns.negociostatus DESC
            , n.codoperacao DESC
            , no.naturezaoperacao DESC
            ";

        $dados['negocios'] = DB::select($sql);

        switch ($filtro['ativo']) {
            case 1:
                $ativo = 'and vc.inativo is null';
                break;

            case 2:
                $ativo = 'and vc.inativo is not null';
                break;

            default:
                $ativo = '';
                break;
        }

        $sql = "
            select
                case when inativo is null then 'Ativo' else 'Inativo' end as status
                , sum(vc.total) as total
                , sum(prazo.aprazo) as aprazo
                , sum(vc.total) - sum(coalesce(prazo.aprazo, 0)) as avista
                , count(vc.codvalecompra) as quantidade
            from tblvalecompra vc
            left join (
                select vcfp.codvalecompra, sum(vcfp.valorpagamento) as aprazo
                from tblvalecompraformapagamento vcfp 
                inner join tblformapagamento fp on (fp.codformapagamento = vcfp.codformapagamento and fp.avista = false)
                group by vcfp.codvalecompra            
            ) prazo on (prazo.codvalecompra = vc.codvalecompra)
            where vc.codusuariocriacao = {$filtro['codusuario']}
            and vc.criacao between '{$filtro['datainicial']}' and '{$filtro['datafinal']}'
            $ativo
            group by case when inativo is null then 'Ativo' else 'Inativo' end
            order by case when inativo is null then 'Ativo' else 'Inativo' end ASC
            ";

        $dados['vales'] = DB::select($sql);
        
        switch ($filtro['ativo']) {
            case 1:
                $ativo = 'and lt.estornado is null';
                break;

            case 2:
                $ativo = 'and lt.estornado is not null';
                break;

            default:
                $ativo = '';
                break;
        }

        $sql = "
            select
                case when estornado is null then 'Ativa' else 'Estornada' end as status
                , sum(coalesce(debito, 0)) as debito
                , sum(coalesce(credito, 0)) as credito
                , count(codliquidacaotitulo) as quantidade
            from tblliquidacaotitulo lt
            where lt.codusuariocriacao = {$filtro['codusuario']}
            and lt.criacao between '{$filtro['datainicial']}' and '{$filtro['datafinal']}'
            $ativo
            group by case when estornado is null then 'Ativa' else 'Estornada' end
            ";

        $dados['liquidacoes'] = DB::select($sql);
        
        return view('caixa.index', ['bc'=>$this->bc, 'dados'=>$dados, 'filtro'=>$filtro]);
    }
}
