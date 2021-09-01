<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li class="text-muted menu-title">Módulos</li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-shopping-cart"></i>
                        <span> Comercial </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url("caixa") }}">Totais de Caixa</a></li>
                        <li><a href="{{ url("vale-compra") }}">Vale Compras</a></li>
                        <li><a href="{{ url("vale-compra-modelo") }}">Modelos de Vale</a></li>
                        <li><a href="{{ url("meta") }}">Meta</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-puzzle-piece"></i>
                        <span> Produtos </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">

                        <li><a href="{{ url('produto') }}">Cadastro</a><li>
                        <li><a href="{{ url('produto/quiosque') }}">Consulta</a><li>
                        <li><a href="{{ url('produto-historico-preco') }}">Histórico de Preços</a></li>
                        <li><a href="{{ url('marca') }}">Marcas</a><li>
                        <li><a href="{{ url('secao-produto') }}">Seções</a><li>
                        <li><a href="{{ url('tipo-produto') }}">Tipos</a></li>
                        <li><a href="{{ url('unidade-medida') }}">Unidades de medida</a></li>
                        <li><a href="{{ url('ncm') }}">NCM</a></li>
                        
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect subdrop">
                              <span>Prancheta</span>
                              <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{ url('prancheta') }}"><span>Categorias</span></a></li>
                                <li><a href="{{ url('prancheta-produto') }}"><span>Produtos</span></a></li>
                            </ul>
                        </li>                        

                    </ul>
                </li>


                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-truck"></i>
                        <span> Estoque </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">

	                <li><a href="{{ url('estoque-saldo') }}">Saldos</a></li>
	                <li><a href="{{ url('estoque-saldo/relatorio-analise-filtro') }}">Análise</a></li>
	                <li><a href="{{ url('estoque-saldo/relatorio-comparativo-vendas-filtro') }}">Venda Filial x Depósito</a></li>
	                <li><a href="{{ url('estoque-saldo/relatorio-fisico-fiscal-filtro') }}">Fisico x Fiscal</a></li>
	                <li><a href="{{ url('estoque-saldo-conferencia') }}">Conferência</a></li>
	                <li><a href="{{ url('estoque-local') }}">Locais</a></li>

                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-user"></i>
                        <span> Pessoas </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('estado-civil') }}">Estado Civil</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-credit-card"></i>
                        <span> Cheques </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('cheque') }}">Cadastros</a></li>
                        <li><a href="{{ url('cheque-repasse') }}">Repasses</a></li>
                        <li><a href="{{ url('cheque-motivo-devolucao') }}">Motivos de Devolução</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-users"></i>
                        <span> Usuários </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('usuario') }}">Cadastro</a></li>
                        <li><a href="{{ url('grupo-usuario') }}">Grupos</a></li>
                        <li><a href="{{ url('permissao') }}">Permissões</a></li>
                    </ul>
                </li>
		
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-gears"></i>
                        <span> Configurações </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('dominio') }}">Exportação Domínio</a></li>
                        <li><a href="{{ url('feriado') }}">Feriado</a></li>
                        <li><a href="{{ url('imagem') }}">Imagens</a></li>
                        <li><a href="{{ url('gerador-codigo') }}">Gerador de código</a></li>
                    </ul>
                </li>
		
            </ul>
            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>

</div>
<!-- Left Sidebar End -->
