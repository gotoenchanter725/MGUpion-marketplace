<?php

    use MGLara\Models\ProdutoEmbalagem;
    
    $embalagens[0] = $model->Produto->UnidadeMedida->sigla;
    
    foreach ($model->Produto->ProdutoEmbalagemS as $pe) {
        $embalagens[$pe->codprodutoembalagem] = $pe->descricao;
    }
    
    $variacoes = $model->Produto->ProdutoVariacaoS()->orderBy('variacao', 'ASC')->orderByRaw('variacao nulls first')->pluck('variacao', 'codprodutovariacao')->all();

    foreach($variacoes as $cod => $descr)
        if (empty($descr))
            $variacoes[$cod] = '{Sem Variação}';
    
    $variacoes = ['' => ''] + $variacoes;
    
?>
{!! Form::hidden('codproduto', Request::get('codproduto')) !!}
<div class="row">
    <div class="col-md-12">
        <fieldset class="form-group">
            {!! Form::label('codprodutovariacao', 'Variação') !!}
            {!! Form::select('codprodutovariacao', $variacoes, null, ['class'=> 'form-control', 'required'=>true, 'id' => 'codprodutovariacao', 'style'=>'width:100%']) !!}
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('codprodutoembalagem', 'Unidade de Medida') !!}
            {!! Form::select('codprodutoembalagem', $embalagens, null, ['class'=> 'form-control', 'id' => 'codprodutoembalagem']) !!}
        </fieldset>
    
        <fieldset class="form-group">
            {!! Form::label('barras', 'Barras') !!}
            <div id="barrasDiv">{!! Form::text('barras', null, ['class'=> 'form-control', 'id'=>'barras', 'maxlength'=>'50']) !!}</div>
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('variacao', 'Detalhes') !!}
            {!! Form::text('variacao', null, ['class'=> 'form-control', 'id'=>'variacao', 'maxlength'=>'100']) !!}
        </fieldset>
        <fieldset class="form-group">
            {!! Form::label('referencia', 'Referencia') !!}
            {!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia', 'maxlength'=>'50']) !!}
        </fieldset>

        <fieldset class="form-group">
           {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
        </fieldset>
    </div>
</div>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script type="text/javascript">
    //http://www.gs1.org/barcodes/support/check_digit_calculator#how
    function calculaDigitoGtin(codigo)
    {
        //preenche com zeros a esquerda
        codigo = "000000000000000000" + codigo;

        //pega 18 digitos
        codigo = codigo.substring(codigo.length-18, codigo.length);
        soma = 0;

        //soma digito par *1 e impar *3
        for (i = 1; i<codigo.length; i++)
        {
            digito = codigo.charAt(i-1);
            if (i === 0 || !!(i && !(i%2)))
                multiplicador = 1;
            else
                multiplicador = 3;
            soma +=  digito * multiplicador;
        }

        //subtrai da maior dezena
        digito = (Math.ceil(soma/10)*10) - soma;	

        //retorna digitocalculado
        return digito;
    }

    //valida o codigo de barras 
    function validaGtin(codigo)
    {
        codigooriginal = codigo;
        codigo = codigo.replace(/[^0-9]/g, '');

        //se estiver em branco retorna verdadeiro
        if (codigo.length == 0) 
            return true;

        //se tiver letras no meio retorna false
        if (codigo.length != codigooriginal.length)
            return false;

        //se nao tiver comprimento adequado retorna false
        if ((codigo.length != 8) 
            && (codigo.length != 12) 
            && (codigo.length != 13) 
            && (codigo.length != 14) 
            && (codigo.length != 18))
            return false;

        //calcula digito e verifica se bate com o digitado
        digito = calculaDigitoGtin(codigo)
        if (digito == codigo.substring(codigo.length-1, codigo.length))
            return true;
        else
            return false;
    }

    function validaBarrasDigitado()
    {
        //inicializa var
        var codigo = $('#barras').val();

        if (validaGtin(codigo)) {
            return true;
        }
        
        return false;
    }

    //mostra aviso sobre digito codigo de barras incorreto
    function mostraPopoverBarras()
    {
        var aberto = !($('#barrasDiv').parent().find('.popover').length === 0);
        var abrir = !validaBarrasDigitado();

        //abre
        if (abrir && !aberto)
        {
            $("#barrasDiv").popover({title: 'Dígito Verificador Inválido!', content: 'Verifique o códito digitado, ele não parece estar em nenhum dos padrões de código de barras, como GTIN, EAN ou UPC!', trigger: 'manual', placement: 'right'});
            $("#barrasDiv").popover('show');
        }

        //fecha
        if (!abrir && aberto)
        {
            $("#barrasDiv").popover('destroy');  	
        }

    }

    function bootboxSalvar(form)
    {
        swal({
            title: "Tem certeza que deseja salvar?",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            form.submit();
          }
        });
    }    
    
$(document).ready(function() {
    $('#form-principal').on("submit", function(e) {
        console.log('ola mundo');
        e.preventDefault();
        var currentForm = this;
        if(!validaBarrasDigitado()){
            swal({
                title: "O código de barras cadastrado parece estar incorreto, deseja mesmo continuar?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm){
              if (isConfirm) {
                bootboxSalvar(currentForm);
              }
            });            
        } else {
            bootboxSalvar(currentForm);
        }

    });
    $("#variacao").Setcase();
    $("#variacao").maxlength({alwaysShow: true});
    /*
    $("#barras").Setcase();
    $("#barras").maxlength({alwaysShow: true});
    $("#referencia").Setcase();
    $("#referencia").maxlength({alwaysShow: true});
    */


        $('#codprodutoembalagem').select2({
            placeholder: 'Embalagem',
            allowClear: true,
            closeOnSelect: true
        });    
        $('#codprodutovariacao').select2({
            placeholder: 'Variação',
            allowClear: true,
            closeOnSelect: true
        });

        $('#barras').keyup(function () {
            mostraPopoverBarras();
        });

        mostraPopoverBarras();    
    
    
    
});
</script>
@endsection