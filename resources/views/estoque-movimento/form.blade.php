
<div class='row'>
  <div class='col-md-2'>
    <fieldset class="form-group">
      {!! Form::label('data', 'Data') !!}
      {!! Form::datetimeLocalMG('data', null, ['class'=> 'form-control text-center', 'id'=>'data', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('transferencia', 'Transferência') !!}
      {!! Form::select2('transferencia', ['0' => 'Movimento', '1' => 'Transferencia'], null, ['class'=> 'form-control text-center', 'id'=>'transferencia', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('codestoquemovimentotipo', 'Tipo do Movimento') !!}
      {!! Form::select2EstoqueMovimentoTipo('codestoquemovimentotipo', null, ['class'=> 'form-control', 'id'=>'codestoquemovimentotipo', 'manual'=>true, 'transferencia'=>false, 'step'=>'1', 'min'=>'1', 'required'=>'required', 'autofocus']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('fiscal', 'Fiscal') !!}
      {!! Form::select2FisicoFiscal('fiscal', null, ['class'=> 'form-control', 'id'=>'fiscal', 'required'=>'required']) !!}
    </fieldset>  
  </div>
  <div class='col-md-3'>
    <fieldset class="form-group">
      {!! Form::label('codproduto', 'Produto') !!}
      {!! Form::select2Produto('codproduto', null, ['class'=> 'form-control', 'id'=>'codproduto', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('codprodutovariacao', 'Variação') !!}
      {!! Form::select2ProdutoVariacao('codprodutovariacao', null, ['class'=> 'form-control', 'codproduto'=>'codproduto', 'id'=>'codprodutovariacao', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('codestoquelocal', 'Local') !!}
      {!! Form::select2EstoqueLocal('codestoquelocal', null, ['class'=> 'form-control', 'id'=>'codestoquelocal', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      <div class='row'>
        <div class='col-md-6'>
          {!! Form::label('saldoquantidade', 'Saldo') !!}
          {!! Form::number('saldoquantidade', null, ['class'=> 'form-control text-right', 'disabled', 'id'=>'saldoquantidade', 'step'=>'0.001', 'min'=>'0.001']) !!}        
        </div>
        <div class='col-md-6'>
          {!! Form::label('saldoquantidade', 'Custo') !!}
          {!! Form::number('customedio', null, ['class'=> 'form-control text-right', 'disabled', 'id'=>'customedio', 'step'=>'0.000001', 'min'=>'0.000001']) !!}        
        </div>
      </div>
    </fieldset>  
  </div>
  <div class='col-md-3'>
    <fieldset class="form-group">
      {!! Form::label('codprodutodestino', 'Destino') !!}
      {!! Form::select2Produto('codprodutodestino', null, ['class'=> 'form-control', 'id'=>'codprodutodestino', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('codprodutovariacaodestino', 'Variação Destino') !!}
      {!! Form::select2ProdutoVariacao('codprodutovariacaodestino', null, ['class'=> 'form-control', 'codproduto'=>'codprodutodestino', 'id'=>'codprodutovariacaodestino', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('codestoquelocaldestino', 'Local Destino') !!}
      {!! Form::select2EstoqueLocal('codestoquelocaldestino', null, ['class'=> 'form-control', 'id'=>'codestoquelocaldestino', 'required'=>'required']) !!}
    </fieldset>  
    <fieldset class="form-group">
      <div class='row'>
        <div class='col-md-6'>
          {!! Form::label('saldoquantidadedestino', 'Saldo') !!}
          {!! Form::number('saldoquantidadedestino', null, ['class'=> 'form-control text-right', 'disabled', 'id'=>'saldoquantidadedestino', 'step'=>'0.001', 'min'=>'0.001']) !!}        
        </div>
        <div class='col-md-6'>
          {!! Form::label('saldoquantidade', 'Custo') !!}
          {!! Form::number('customediodestino', null, ['class'=> 'form-control text-right', 'disabled', 'id'=>'customediodestino', 'step'=>'0.000001', 'min'=>'0.000001']) !!}        
        </div>
      </div>
    </fieldset>  
  </div>  
  
  <div class='col-md-2'>
    <fieldset class="form-group">
      {!! Form::label('entradaquantidade', 'Quantidade Entrada') !!}
      {!! Form::number('entradaquantidade', null, ['class'=> 'form-control text-right', 'id'=>'entradaquantidade', 'step'=>'0.001', 'min'=>'0.001']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('entradavalorunitario', 'Custo Entrada') !!}
      {!! Form::number('entradavalorunitario', null, ['class'=> 'form-control text-right', 'id'=>'entradavalorunitario', 'step'=>'0.000001', 'min'=>'0.000001']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('entradavalor', 'Total Entrada') !!}
      {!! Form::number('entradavalor', null, ['class'=> 'form-control text-right', 'id'=>'entradavalor', 'step'=>'0.01', 'min'=>'0.01']) !!}
    </fieldset>  
  </div>  
  
  <div class='col-md-2'>
    <fieldset class="form-group">
      {!! Form::label('saidaquantidade', 'Quantidade Saída') !!}
      {!! Form::number('saidaquantidade', null, ['class'=> 'form-control text-right', 'id'=>'saidaquantidade', 'step'=>'0.001', 'min'=>'0.001']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('saidavalorunitario', 'Custo Saída') !!}
      {!! Form::number('saidavalorunitario', null, ['class'=> 'form-control text-right', 'id'=>'saidavalorunitario', 'step'=>'0.000001', 'min'=>'0.000001']) !!}
    </fieldset>  
    <fieldset class="form-group">
      {!! Form::label('saidavalor', 'Total Saída') !!}
      {!! Form::number('saidavalor', null, ['class'=> 'form-control text-right', 'id'=>'saidavalor', 'step'=>'0.01', 'min'=>'0.01']) !!}
    </fieldset>  
  </div>  
</div>

<fieldset class="form-group">
    {!! Form::label('observacoes', 'Observacoes') !!}
    {!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'rows' => 3, 'id'=>'observacoes', 'maxlength'=>'200']) !!}
</fieldset>
<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script type="text/javascript">
<?php
use MGLara\Models\EstoqueMovimentoTipo;
$tipos = EstoqueMovimentoTipo::select('codestoquemovimentotipo')->where('manual', true)->where('preco', 1)->get();
$tipoPrecoInformado = [];
foreach ($tipos as $tipo) {
    $tipoPrecoInformado[] = $tipo->codestoquemovimentotipo;
}
?>
function habilitaValores()
{
    var tipoPrecoInformado = {{ json_encode($tipoPrecoInformado) }};
    var codestoquemovimentotipo = parseInt($('#codestoquemovimentotipo').val());
    
    if ($('#transferencia').val() == 1) {
        $("#entradaquantidade").prop('disabled', true);
        $("#entradavalorunitario").prop('disabled', true);
        $("#saidavalorunitario").prop('disabled', true);
        $("#entradavalor").prop('disabled', true);
        $("#saidavalor").prop('disabled', true);
    } else if ($.inArray(codestoquemovimentotipo, tipoPrecoInformado) >= 0) {
        $("#entradaquantidade").removeAttr('disabled');
        $("#entradavalorunitario").removeAttr('disabled');
        $("#saidavalorunitario").removeAttr('disabled');
        $("#entradavalor").removeAttr('disabled');
        $("#saidavalor").removeAttr('disabled');
    } else {
        $("#entradaquantidade").removeAttr('disabled');
        $("#entradavalorunitario").prop('disabled', true);
        $("#saidavalorunitario").prop('disabled', true);
        $("#entradavalor").prop('disabled', true);
        $("#saidavalor").prop('disabled', true);
    }
    
}

function calculaUnitario(campo) 
{
    var qtd = $('#' + campo + 'quantidade').val();
    var tot = $('#' + campo + 'valor').val();
    var unitario = tot;
    if (qtd > 0) {
        unitario = Math.round((tot/qtd)*1000000)/1000000;
    }
    $('#' + campo + 'valorunitario').val(unitario);
}    

function calculaTotal(campo) 
{
    var qtd = $('#' + campo + 'quantidade').val();
    if (qtd == '') {
        qtd = 1;
    }
    var un = $('#' + campo + 'valorunitario').val();
    var total = Math.round(qtd * un * 100) / 100;
    if (un == '') {
        $('#' + campo + 'valor').val(null);
    } else {
        $('#' + campo + 'valor').val(total);
    }
}   

function habilitaTipo()
{
    if ($('#transferencia').val() == 1) {
        $("#codestoquemovimentotipo").prop('disabled', true);
        $("#codprodutodestino").removeAttr('disabled');
        $("#codprodutovariacaodestino").removeAttr('disabled');
        $("#codestoquelocaldestino").removeAttr('disabled');
    } else {
        $("#codestoquemovimentotipo").removeAttr('disabled');
        $("#codprodutodestino").prop('disabled', true);
        $("#codprodutovariacaodestino").prop('disabled', true);
        $("#codestoquelocaldestino").prop('disabled', true);
    }
    
    habilitaValores();
}

    
$(document).ready(function() {
    
    calculaUnitario('entrada');
    calculaUnitario('saida');
    habilitaValores();
    habilitaTipo();
    
    $('#form-principal').on("submit", function(e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja salvar?",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          }
        });
    });
    
    $('#transferencia').change(function() {
        habilitaTipo();
    });
    
    
    $('#codestoquemovimentotipo').change(function() {
        habilitaValores();
    });
    
    $('#entradaquantidade').change(function() {
        if ($('#entradavalorunitario').val() == '' && $('#entradaquantidade').val() != '') {
            $('#entradavalorunitario').val($('#customedio').val());
        }
        calculaTotal('entrada');
    });
    $('#entradavalorunitario').change(function() {
        calculaTotal('entrada');
    });
    $('#entradavalor').change(function() {
        calculaUnitario('entrada');
    });
    
    $('#saidaquantidade').change(function() {
        if ($('#saidavalorunitario').val() == '' && $('#saidaquantidade').val() != '') {
            $('#saidavalorunitario').val($('#customedio').val());
        }
        calculaTotal('saida');
    });
    $('#saidavalorunitario').change(function() {
        calculaTotal('saida');
    });
    $('#saidavalor').change(function() {
        calculaUnitario('saida');
    });
    
    
    $("#observacoes").maxlength({alwaysShow: true});
});
</script>
@endsection