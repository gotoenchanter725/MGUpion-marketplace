<?php

namespace MGLara\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        
        // Policies Vinculadas ao Repository
        \MGLara\Repositories\DominioRepository::class      => \MGLara\Policies\DominioPolicy::class,
        
        // Policies Vinculadas ao Model
        \MGLara\Models\GrupoUsuario::class          => \MGLara\Policies\GrupoUsuarioPolicy::class,
        \MGLara\Models\Marca::class                 => \MGLara\Policies\MarcaPolicy::class,
        \MGLara\Models\Permissao::class             => \MGLara\Policies\PermissaoPolicy::class,
        \MGLara\Models\UnidadeMedida::class         => \MGLara\Policies\UnidadeMedidaPolicy::class,
        \MGLara\Models\Usuario::class               => \MGLara\Policies\UsuarioPolicy::class,
        \MGLara\Models\SecaoProduto::class          => \MGLara\Policies\SecaoProdutoPolicy::class,
        \MGLara\Models\FamiliaProduto::class        => \MGLara\Policies\FamiliaProdutoPolicy::class,
        \MGLara\Models\GrupoProduto::class          => \MGLara\Policies\GrupoProdutoPolicy::class,
        \MGLara\Models\SubGrupoProduto::class       => \MGLara\Policies\SubGrupoProdutoPolicy::class,
        \MGLara\Models\TipoProduto::class           => \MGLara\Policies\TipoProdutoPolicy::class,
        \MGLara\Models\Banco::class                 => \MGLara\Policies\BancoPolicy::class,
        \MGLara\Models\Pais::class                  => \MGLara\Policies\PaisPolicy::class,
        \MGLara\Models\EstadoCivil::class           => \MGLara\Policies\EstadoCivilPolicy::class,        
        \MGLara\Models\ValeCompra::class            => \MGLara\Policies\ValeCompraPolicy::class,
        \MGLara\Models\Feriado::class               => \MGLara\Policies\FeriadoPolicy::class,
        \MGLara\Models\ValeCompraModelo::class      => \MGLara\Policies\ValeCompraModeloPolicy::class,
        \MGLara\Models\Cargo::class                 => \MGLara\Policies\CargoPolicy::class,
        \MGLara\Models\ChequeMotivoDevolucao::class => \MGLara\Policies\ChequeMotivoDevolucaoPolicy::class,
        \MGLara\Models\Cheque::class                => \MGLara\Policies\ChequePolicy::class,
        \MGLara\Models\Ncm::class                   => \MGLara\Policies\NcmPolicy::class,
        \MGLara\Models\ProdutoHistoricoPreco::class => \MGLara\Policies\ProdutoHistoricoPrecoPolicy::class,
        \MGLara\Models\ProdutoBarra::class          => \MGLara\Policies\ProdutoBarraPolicy::class,
        \MGLara\Models\EstoqueSaldoConferencia::class => \MGLara\Policies\EstoqueSaldoConferenciaPolicy::class,
        \MGLara\Models\EstoqueSaldo::class          => \MGLara\Policies\EstoqueSaldoPolicy::class,
        \MGLara\Models\TipoMovimentoTitulo::class   => \MGLara\Policies\TipoMovimentoTituloPolicy::class,
        \MGLara\Models\Meta::class                  => \MGLara\Policies\MetaPolicy::class,
        \MGLara\Models\Produto::class               => \MGLara\Policies\ProdutoPolicy::class,
        \MGLara\Models\EstoqueMes::class            => \MGLara\Policies\EstoqueMesPolicy::class,
        \MGLara\Models\NegocioProdutoBarra::class   => \MGLara\Policies\NegocioProdutoBarraPolicy::class,
        \MGLara\Models\EstoqueLocal::class          => \MGLara\Policies\EstoqueLocalPolicy::class,
        \MGLara\Models\Cest::class                  => \MGLara\Policies\CestPolicy::class,
        \MGLara\Models\Imagem::class                => \MGLara\Policies\ImagemPolicy::class,
        \MGLara\Models\ProdutoBarra::class          => \MGLara\Policies\ProdutoBarraPolicy::class,
        \MGLara\Models\ProdutoVariacao::class       => \MGLara\Policies\ProdutoVariacaoPolicy::class,
        \MGLara\Models\EstoqueMovimento::class      => \MGLara\Policies\EstoqueMovimentoPolicy::class,
        \MGLara\Models\ChequeRepasse::class         => \MGLara\Policies\ChequeRepassePolicy::class,
        \MGLara\Models\Prancheta::class             => \MGLara\Policies\PranchetaPolicy::class,
        \MGLara\Models\PranchetaProduto::class      => \MGLara\Policies\PranchetaProdutoPolicy::class,
        \MGLara\Models\NotaFiscalProdutoBarra::class => \MGLara\Policies\NotaFiscalProdutoBarraPolicy::class,
        \MGLara\Models\ProdutoEmbalagem::class => \MGLara\Policies\ProdutoEmbalagemPolicy::class,
        \MGLara\Models\Caixa::class                 => \MGLara\Policies\CaixaPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
    
    public function getPolicies($model = null) 
    {
        if (!empty($model)) {
            if (isset($this->policies[$model])) {
                return $this->policies[$model];
            } else {
                return false;
            }
        }
        return $this->policies;
    }
}
