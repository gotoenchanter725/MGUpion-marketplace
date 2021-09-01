<?php
//use Blade;

use Collective\Html\FormFacade;

Form::macro('select2', function($name, $list = [], $selected = null, $options = [])
{
    if (empty($options['id']))
        $options['id'] = $name;

    if (empty($options['placeholder']))
        $options['placeholder'] = 'Selecione...';

    if (empty($options['allowClear']))
        $options['allowClear'] = true;
    $options['allowClear'] = ($options['allowClear'])?'true':'false';

    if (empty($options['closeOnSelect']))
        $options['closeOnSelect'] = true;
    $options['closeOnSelect'] = ($options['closeOnSelect'])?'true':'false';

    $script = <<< END

        <script type="text/javascript">
            $(document).ready(function() {
                $('#{$options['id']}').select2({
                    placeholder: '{$options['placeholder']}',
                    allowClear: {$options['allowClear']},
                    closeOnSelect: {$options['closeOnSelect']},
                    language:'pt-BR',
                });
            });
        </script>
END;

    unset($options['placeholder']);

    $campo = Form::select($name, $list, $selected, $options);

    return $campo . $script;
});

Form::macro('select2Marca', function($name, $value = null, $options = [])
{

    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    
    $placeholder = $options['placeholder']??'Marca';
    
    $minimumInputLength = $options['minimumInputLength']??1;
    
    $allowClear = ($options['allowClear']??true)?'true':'false';
    
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    
    $cache = ($options['cache']??true)?'true':'false';
    
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';

    $script = <<< END

    <script type="text/javascript">
        
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/marca/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/marca/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {
                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "<div class='clearfix'>";
                markup    += "<strong class='" + css_titulo + "'>" + repo.marca + "</strong>";
                markup    += "</div>";
                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.marca;
            },
                    
        });
                    
    });

    </script>
END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});

/* UNIDADES DE MEDIDA */
Form::macro('select2UnidadeMedida', function($name, $selected = null, $options = [])
{
    if (empty($options['campo']))
        $options['campo'] = 'sigla';
    $medidas = MGLara\Models\UnidadeMedida::orderBy('unidademedida')->pluck($options['campo'], 'codunidademedida')->prepend('', '');
    return Form::select2($name, $medidas, $selected, $options);
});

/* UNIDADES USUÁRIO */
Form::macro('select2Usuario', function($name, $selected = null, $options = [])
{
    $usuarios = MGLara\Models\Usuario::orderBy('usuario')->pluck('usuario', 'codusuario')->prepend('', '');
    return Form::select2($name, $usuarios, $selected, $options);
});

/* MOTIVOS DEVOLUÇÃO :: CHEQUE */
Form::macro('select2Chequemotivodevolucao', function($name, $selected = null, $options = [])
{
    $motivosdevolucao = MGLara\Models\ChequeMotivoDevolucao::orderBy('numero')->get();
    $mots = [];
    foreach($motivosdevolucao as $row){
        $mots[$row['codchequemotivodevolucao']] = $row['numero'].' - '.$row['chequemotivodevolucao'];
    }
    return Form::select2($name, $mots, $selected, $options);

    
});

/* GRUPO CLIENTE */
Form::macro('select2GrupoCliente', function($name, $selected = null, $options = [])
{
    $grupos = MGLara\Models\GrupoCliente::orderBy('grupocliente')->pluck('grupocliente', 'codgrupocliente')->prepend('', '');
    return Form::select2($name, $grupos, $selected, $options);
});

/* ATIVO */
Form::macro('select2Ativo', function($name, $selected = null, $options = [])
{
    return Form::select2Inativo($name, $selected, $options);
});
Form::macro('select2Inativo', function($name, $selected = null, $options = [])
{
    $opcoes = ['' => '', 1 => 'Ativos', 2 => 'Inativos', 9 => 'Todos'];
    $options['placeholder'] = 'Ativos';
    return Form::select2($name, $opcoes, $selected, $options);
});

/* PRODUTO VARIAÇÃO */
Form::macro('select2ProdutoVariacao', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    
    $placeholder = $options['placeholder']??'Variação...';

    $minimumInputLength = $options['minimumInputLength']??0;
    
    $allowClear = ($options['allowClear']??true)?'true':'false';
    
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    
    $cache = ($options['cache']??true)?'true':'false';
    
    $script = <<< END
    <script type="text/javascript">
        
    $(document).ready(function() {
        
        $('#{$options['codproduto']}').change( function (e) {
            $('#{$id}').val(null).trigger('change.select2');
        });
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/produto-variacao/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params, 
                        codproduto: $('#{$options['codproduto']}').val()
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/produto-variacao/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {

                if (repo.loading) return repo.text;

                var markup = "<div class='clearfix'>";
                markup    += repo.variacao;
                markup    += "</div>";
                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.variacao;
            },
                    
        });
                    
    });

    </script>

END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});



/* SEÇÃO DE PRODUTO */
Form::macro('select2SecaoProduto', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder']))
        $options['placeholder'] = 'Seção...';

    $secoes = MGLara\Models\SecaoProduto::orderBy('secaoproduto')->pluck('secaoproduto', 'codsecaoproduto')->prepend('', '');
    $campo = Form::select2($name, $secoes, $selected, $options);
    return $campo;
});


/* FAMÍLIA DE PRODUTO */
Form::macro('select2FamiliaProduto', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    
    $placeholder = $options['placeholder']??'Família';
    
    $minimumInputLength = $options['minimumInputLength']??0;
    
    $allowClear = ($options['allowClear']??true)?'true':'false';
    
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    
    $cache = ($options['cache']??true)?'true':'false';
    
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';

    $script = <<< END

    <script type="text/javascript">
        
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl + '/familia-produto/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                        codsecaoproduto: $('#{$options['codsecaoproduto']}').val()
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/familia-produto/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {

                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "text-muted";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "<div class='clearfix'>";
                markup    += "<strong class='" + css_titulo + "'>" + repo.familiaproduto + "</strong>";
                markup    += "</div>";

                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.familiaproduto;
            },
                    
        });
                    
    });

    </script>
END;

    if(isset($options['codsecaoproduto'])) {
    $script .= <<< END
    <script type="text/javascript">
        $(document).ready(function() {
            $('#{$options['codsecaoproduto']}').on('change', function (e) {
                e.preventDefault();
                $('#{$options['id']}').select2('val', null);
            });
        });
    </script>
END;
}

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});


/* GRUPO DE PRODUTO */
Form::macro('select2GrupoProduto', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    
    $placeholder = $options['placeholder']??'Grupo';
    
    $minimumInputLength = $options['minimumInputLength']??0;
    
    $allowClear = ($options['allowClear']??true)?'true':'false';
    
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    
    $cache = ($options['cache']??true)?'true':'false';
    
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';

    $script = <<< END

    <script type="text/javascript">
        
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/grupo-produto/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                        codfamiliaproduto: $('#{$options['codfamiliaproduto']}').val()
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/grupo-produto/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {

                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "text-muted";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "<div class='clearfix'>";
                markup    += "<strong class='" + css_titulo + "'>" + repo.grupoproduto + "</strong>";
                markup    += "</div>";

                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.grupoproduto;
            },
                    
        });
                    
    });

    </script>
END;

    if(isset($options['codfamiliaproduto'])) {
    $script .= <<< END
    <script type="text/javascript">
        $(document).ready(function() {
            $('#{$options['codfamiliaproduto']}').on('change', function (e) {
                e.preventDefault();
                $('#{$options['id']}').select2('val', null);
            });
        });
    </script>
END;
}

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});


/* SUBGRUPO DE PRODUTO */
Form::macro('select2SubGrupoProduto', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    
    $placeholder = $options['placeholder']??'Sub Grupo';
    
    $minimumInputLength = $options['minimumInputLength']??0;
    
    $allowClear = ($options['allowClear']??true)?'true':'false';
    
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    
    $cache = ($options['cache']??true)?'true':'false';
    
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';

    $script = <<< END

    <script type="text/javascript">
        
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/sub-grupo-produto/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                        codgrupoproduto: $('#{$options['codgrupoproduto']}').val()
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/sub-grupo-produto/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {

                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "text-muted";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "<div class='clearfix'>";
                markup    += "<strong class='" + css_titulo + "'>" + repo.subgrupoproduto + "</strong>";
                markup    += "</div>";

                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.subgrupoproduto;
            },
                    
        });
                    
    });

    </script>
END;

    if(isset($options['codgrupoproduto'])) {
    $script .= <<< END
    <script type="text/javascript">
        $(document).ready(function() {
            $('#{$options['codgrupoproduto']}').on('change', function (e) {
                e.preventDefault();
                $('#{$options['id']}').select2('val', null);
            });
        });
    </script>
END;
}

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});


/* NCM */
Form::macro('select2Ncm', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    $placeholder = $options['placeholder']??'Ncm';
    $minimumInputLength = $options['minimumInputLength']??1;
    $allowClear = ($options['allowClear']??true)?'true':'false';
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    $cache = ($options['cache']??true)?'true':'false';
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';
    $script = <<< END
    <script type="text/javascript">
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/ncm/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/ncm/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {

                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "text-muted";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "<div class='clearfix'>";
                markup    += "<b>" + repo.ncm + "</b>&nbsp;";
                markup    += "<span>" + repo.descricao + "</span>";
                markup    += "</div>";

                return markup; 
            },
                    
            templateSelection: function (repo) {
                return '<strong>' + repo.ncm + '</strong>' + "&nbsp;" + repo.descricao;
            },
                    
        });
                    
    });

    </script>
END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});


/* TRIBUTAÇÃO */
Form::macro('select2Tributacao', function($name, $selected = null, $options = [])
{
    $tributacoes = MGLara\Models\Tributacao::orderBy('tributacao')->pluck('tributacao', 'codtributacao')->prepend('', '');
    return Form::select2($name, $tributacoes, $selected, $options);
});

/* TIPO PRODUTO */
Form::macro('select2TipoProduto', function($name, $selected = null, $options = [])
{
    $tipos = MGLara\Models\TipoProduto::orderBy('tipoproduto')->pluck('tipoproduto', 'codtipoproduto')->prepend('', '');
    return Form::select2($name, $tipos, $selected, $options);
});

/* EMPRESA */
Form::macro('select2Empresa', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Empresa';
    $regs = MGLara\Models\Empresa::orderBy('codempresa')->pluck('empresa', 'codempresa')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* FILIAL */
Form::macro('select2Filial', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Filial';
    $regs = MGLara\Models\Filial::orderBy('codfilial')->pluck('filial', 'codfilial')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* BANCO */
Form::macro('select2Banco', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Banco';
    $regs = MGLara\Models\Banco::orderBy('codbanco')->pluck('banco', 'codbanco')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* ECF */
Form::macro('select2Ecf', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'ECF';
    $regs = MGLara\Models\Ecf::orderBy('codecf')->pluck('ecf', 'codecf')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* PORTADORES */
Form::macro('select2Portador', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Portador';
    $regs = MGLara\Models\Portador::orderBy('codportador')->pluck('portador', 'codportador')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* OPERAÇÃO */
Form::macro('select2Operacao', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Operação';
    $regs = MGLara\Models\Operacao::orderBy('codoperacao')->pluck('operacao', 'codoperacao')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* ESTOQUE LOCAL */
Form::macro('select2EstoqueLocal', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Local Estoque';
    $regs = MGLara\Models\EstoqueLocal::orderBy('codestoquelocal')->pluck('estoquelocal', 'codestoquelocal')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* NATUREZA OPERACAO */
Form::macro('select2NaturezaOperacao', function($name, $selected = null, $options = [])
{
    if (empty($options['placeholder'])) $options['placeholder'] = 'Natureza de Operação';
    $regs = MGLara\Models\NaturezaOperacao::orderBy('naturezaoperacao')->pluck('naturezaoperacao', 'codnaturezaoperacao')->prepend('', '');
    return Form::select2($name, $regs, $selected, $options);
});

/* CEST */

Form::macro('select2Cest', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    
    $placeholder = $options['placeholder']??'Cest';
    
    $minimumInputLength = $options['minimumInputLength']??0;
    
    $allowClear = ($options['allowClear']??true)?'true':'false';
    
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    
    $cache = ($options['cache']??true)?'true':'false';
    
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';

    $script = <<< END

    <script type="text/javascript">
        
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl + '/cest/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                        codncm: $('#{$options['codncm']}').val()
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/cest/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {

                if (repo.loading) return repo.text;

                var markup = "";
                markup += "<b>" + repo.ncm + "</b>/";
                markup += "<b>" + repo.cest + "</b>&nbsp;";
                markup += "<span>" + repo.descricao + "</span>";
                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.ncm + "/" + repo.cest + "&nbsp;" + repo.descricao;
            },
                    
        });
                    
    });

    </script>
END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});


/* PESSOA */

Form::macro('select2Pessoa', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    
    $placeholder = $options['placeholder']??'Pessoa';
    
    $minimumInputLength = $options['minimumInputLength']??3;
    
    $allowClear = ($options['allowClear']??true)?'true':'false';
    
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    
    $cache = ($options['cache']??true)?'true':'false';
    
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';

    $script = <<< END

    <script type="text/javascript">
        
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/pessoa/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/pessoa/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {

                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "text-muted";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var nome = repo.fantasia;

                //if (repo.inclusaoSpc != 0)
                //  nome += "&nbsp<span class=\"label label-warning\">" + repo.inclusaoSpc + "</span>";

                var markup = "<div class='clearfix'>";
                markup    += "<strong class='" + css_titulo + "'>" + nome + "</strong>";
                markup    += "<small class='pull-right " + css_detalhes + "'>#" + repo.codpessoa + "</small>";
                markup    += "<br>";
                markup    += "<small class='" + css_detalhes + "'>" + repo.pessoa + "</small>";
                markup    += "<small class='pull-right " + css_detalhes + "'>" + repo.cnpj + "</small>";
                markup    += "</div>";

                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.fantasia;
            },
                    
        });
                    
    });

    </script>
END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});

/* CIDADE*/
Form::macro('select2Cidade', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    $placeholder = $options['placeholder']??'Cidade';
    $minimumInputLength = $options['minimumInputLength']??3;
    $allowClear = ($options['allowClear']??true)?'true':'false';
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    $cache = ($options['cache']??true)?'true':'false';
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';
    $script = <<< END
    <script type="text/javascript">
    $(document).ready(function() {
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/cidade/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/cidade/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {
                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "";
                markup    += repo.cidade + "<span class='pull-right'>" + repo.uf + "</span>";
                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.cidade;
            },
                    
        });
                    
    });

    </script>
END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});

/* PRODUTO*/
Form::macro('select2Produto', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    $placeholder = $options['placeholder']??'Produto';
    $minimumInputLength = $options['minimumInputLength']??3;
    $allowClear = ($options['allowClear']??true)?'true':'false';
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    $cache = ($options['cache']??true)?'true':'false';
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';
    $script = <<< END
    <script type="text/javascript">
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/produto/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/produto/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {
                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "";
                markup    += "<span class="+ css_titulo +"><small class=\"text-muted\">"+ repo.codigo +"</small> "+repo.produto + "<span class='pull-right'>R$ " + repo.preco + "</span>";
                markup    += "<br>";
                markup    += "<small class='" + css_detalhes + "'>";
                markup    += repo.secaoproduto;
                markup    += " » " + repo.familiaproduto;
                markup    += " » " + repo.grupoproduto;
                markup    += " » " + repo.subgrupoproduto;
                markup    += " » " + repo.marca;
                if (repo.referencia) {
                    markup    += " » " + repo.referencia;
                }
                markup    += "</small>";
                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.produto;
            },
                    
        });
                    
    });

    </script>
END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});

/* PRODUTO Barra */
Form::macro('select2ProdutoBarra', function($name, $value = null, $options = [])
{
    $options['id'] = $options['id']??$name;
    $id = $options['id'];
    $placeholder = $options['placeholder']??'Produto';
    $minimumInputLength = $options['minimumInputLength']??3;
    $allowClear = ($options['allowClear']??true)?'true':'false';
    $closeOnSelect = ($options['closeOnSelect']??true)?'true':'false';
    $cache = ($options['cache']??true)?'true':'false';
    $somenteAtivos = ($options['somenteAtivos']??true)?'true':'false';
    $script = <<< END
    <script type="text/javascript">
    $(document).ready(function() {
        
        $('#{$id}').select2({
        
            placeholder: '{$placeholder}',
            minimumInputLength: {$minimumInputLength},
            allowClear: {$allowClear},
            closeOnSelect: {$closeOnSelect},
            cache: {$cache},
            language:'pt-BR',
            escapeMarkup: function (markup) { return markup; },
            
            ajax:{
                url:baseUrl+'/produto-barra/select2',
                delay: 300,
                dataType:'json',
                data: function (params) {
                    return {
                        params: params,
                        somenteAtivos: {$somenteAtivos},
                    };
                },
            },
            
            initSelection:function (element, callback) {
                $.ajax({
                    type: "GET",
                    url: baseUrl+"/produto-barra/select2",
                    data: "id="+$('#{$id}').val(),
                    dataType: "json",
                    success: function(result) { 
                        callback(result); 
                    }
                });
            },
                    
            templateResult: function (repo) {
                if (repo.loading) return repo.text;

                var css_titulo = "";
                var css_detalhes = "";
                if (repo.inativo) {
                    css_titulo = "text-danger";
                    css_detalhes = "text-danger";
                }

                var markup = "";
                markup    += "<div class='row "+ css_titulo +"'>";

                markup    += "<strong class='col-md-9'>";
                markup    += repo.produto + "";
                markup    += "</strong>";

                markup    += "<div class='col-md-3'>";
                markup    += "<small class='pull-left'>" + repo.unidademedida + "</small>";
                markup    += "<span class='pull-right'> " + repo.preco + "</span>";
                markup    += "</div>";

                markup    += "</div>";

                markup    += "<small class='" + css_detalhes + "'>";
                markup    += "<strong>" + repo.barras + "</strong>";
                markup    += " » " + repo.codproduto;
                markup    += " » " + repo.secaoproduto;
                markup    += " » " + repo.familiaproduto;
                markup    += " » " + repo.grupoproduto;
                markup    += " » " + repo.subgrupoproduto;
                markup    += " » " + repo.marca;
                if (repo.referencia) {
                    markup    += " » " + repo.referencia;
                }
                markup    += "</small>";
                return markup; 
            },
                    
            templateSelection: function (repo) {
                return repo.produto;
            },
                    
        });
                    
    });

    </script>
END;

    $value_form = Form::getValueAttribute($name)??$value;
    $value_form = empty($value_form)?$value:$value_form;
    $campo = Form::select($name, [$value_form => ' ... Carregando ... '], $value, $options);

    return $campo . $script;
});

/* ESTOQUE MOVIMENTO TIPO */
Form::macro('select2EstoqueMovimentoTipo', function($name, $selected = null, $options = [])
{
    if (!isset($options['manual'])) {
        $options['manual'] = false;
    }

    if (!isset($options['transferencia'])) {
        $options['transferencia'] = false;
    }

    $qry = MGLara\Models\EstoqueMovimentoTipo::orderBy('descricao');
    
    if ($options['manual']) {
        $qry = $qry->where('manual', '=', true);
    }
    
    if (!$options['transferencia']) {
        $qry = $qry->where('transferencia', '=', false);
    }
    
    $op = $qry->pluck('descricao', 'codestoquemovimentotipo')->prepend('', '');

    return Form::select2($name, $op, $selected, $options);
});

/* Vale Compra Modelo */
Form::macro('select2ValeCompraModelo', function($name, $selected = null, $options = [])
{
    $options['ativo'] = (isset($options['ativo']))?$options['ativo']:1;
    $options['placeholder'] = (isset($options['placeholder']))?$options['placeholder']:'Modelo de Vale Compras';

    $qry = MGLara\Models\ValeCompraModelo::orderBy('modelo');
    switch ($options['ativo']) {
        case 1:
            $qry->whereNull('inativo');
            break;
        case 2:
            $qry->whereNotNull('inativo');
            break;
    }
    $valores = $qry->pluck('modelo', 'codvalecompramodelo')->prepend('', '');
    return Form::select2($name, $valores, $selected, $options);
});


/* Forma de Pagamento */
Form::macro('select2FormaPagamento', function($name, $selected = null, $options = [])
{
    $options['ativo'] = (isset($options['ativo']))?$options['ativo']:1;
    $options['placeholder'] = (isset($options['placeholder']))?$options['placeholder']:'Forma de Pagamento';

    $qry = MGLara\Models\FormaPagamento::orderBy('formapagamento');
    /*
    switch ($options['ativo']) {
        case 1:
            $qry->whereNull('inativo');
            break;
        case 2:
            $qry->whereNotNull('inativo');
            break;
    }
     *
     */
    $valores = $qry->pluck('formapagamento', 'codformapagamento')->prepend('', '');
    return Form::select2($name, $valores, $selected, $options);
});

/* Fisico Fiscal */
Form::macro('select2FisicoFiscal', function($name, $selected = null, $options = [])
{
    $opcoes = [0 => 'Físico', 1 => 'Fiscal'];
    $options['placeholder'] = 'Físico / Fiscal';
    return Form::select2($name, $opcoes, $selected, $options);
});

/* UNIDADES DE MEDIDA */
Form::macro('select2Prancheta', function($name, $selected = null, $options = [])
{
    $medidas = MGLara\Models\Prancheta::orderBy('prancheta')->pluck('prancheta', 'codprancheta')->prepend('', '');
    return Form::select2($name, $medidas, $selected, $options);
});

