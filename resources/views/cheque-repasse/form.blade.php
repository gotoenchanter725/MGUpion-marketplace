<div class="col-md-12">
    <div class="card">
        <div class="card-block">
            <div class='row'>
                <div class="form-group col-md-2">
                    {!! Form::label('vencimento_de', 'De', ['control-label']) !!}
                    {!! Form::date('vencimento_de', '', ['class'=> 'form-control text-right', 'id'=>'vencimento_de']) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('vencimento_ate', 'Até', ['control-label']) !!}
                    {!! Form::date('vencimento_ate', '', ['class'=> 'form-control text-right', 'id'=>'vencimento_ate']) !!}
                </div>
                <div class="form-group col-md-2">
                    <label class="col-md-12">&nbsp;</label>
                    <button type="button" id="pesquisar" class="btn btn-primary"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Filtro:</div>
                <div style="width:100%; height:300px; overflow-y:auto;">
                    <table class="table table-striped" id="items">
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Cheques selecionados:</div>
                <div style="width:100%;  height:300px; overflow-y:auto;">
                    <table class="table table-striped" id="recebedados">
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="totais" class="card">
                <div class="card-block">
                    <strong>Total Selecionado:</strong> R$ <span id="totalselecionado">0.00</span><br>
                    <strong>Nº de Cheques Selecionados:</strong> <span id="numerocheques">0</span>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-6">
                            {!! Form::label('codportador', 'Portador:', []) !!}
                            {!! Form::select2Portador('codportador', null, ['class'=> 'form-control', 'id'=>'codportador', 'placeholder'=>'Portador', 'required'=>'required']) !!}
                        </div>
                        <div class="form-group col-md-4">
                            {!! Form::label('data', 'Data', []) !!}
                            {!! Form::date('data', formataData($model->vencimento,"Y-m-d"), ['class'=> 'form-control', 'id'=>'data', 'required'=>'required']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('observacao', 'Observação:', []) !!}
                            {!! Form::textarea('observacao', null, ['class'=> 'form-control', 'rows'=>'3', 'id'=>'observacao']) !!}
                        </div>
                        <fieldset class="form-group col-md-12">
                                {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script type="text/javascript">
var stotal = 0;
var ntotal = 0;
$(document).ready(function() {
    
    $(':input:enabled:visible:first').focus();
    $('#chequemotivodevolucao').Setcase();
  
    $("#pesquisar").click(function() {
        var vencimento_de = $("#vencimento_de").val();
        var vencimento_ate = $("#vencimento_ate").val();

        if((vencimento_de=='' || vencimento_de== null) && (vencimento_ate=='' || vencimento_ate== null)){
            swal({
               title: "Faça o filtro para prosseguir",
               type: "warning",
               showCancelButton: true,
               closeOnConfirm: false,
               closeOnCancel: true
            });
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '{{ url('cheque-repasse/consulta') }}/',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {'vencimento_de':vencimento_de,'vencimento_ate':vencimento_ate},
            success: function(retorno) {
                
                //console.log(retorno.data);
                //if(retorno.recordsFiltered>0){
                    ListaCheques(retorno.data,'items');
                //}
            },
            error: function (XHR, textStatus) {
                swal({
                    title: "Erro",
                    type: "error",
                    showCancelButton: false ,
                    closeOnConfirm: true,
                    closeOnCancel: true
                });
                console.log(XHR);
                console.log(textStatus);
            }
        });

    });
    
    $('#form-principal').on("submit", function(e) {
        e.preventDefault();
        
        if(ntotal==0){
            swal({
                title: "Para prosseguir, é necessario ter ao mínimo 1 cheque selecionado.",
                type: "error",
                showCancelButton: false ,
                closeOnConfirm: true,
                closeOnCancel: true
            });
            return false();
        }
        
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja salvar?",
          type: "warning",
          showCancelButton: false,
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          }
        });
    });
    $("#observacoes").Setcase();
    $("#observacoes").maxlength({alwaysShow: true});
    
    $('#items input[type="checkbox"]').on( "click", function() {
        copia();
        somatotal();
        
    });
});
function seleciona(div){
    copia();
    somatotal();
}
function copia(){
    $('#items input[type="checkbox"]').each(function() {
        if ($(this).is(":checked")) {
            $(this).parent().parent().clone().appendTo("#recebedados");
            $(this).parent().parent().remove();
        }
    });
}
function somatotal(){
    stotal = 0;
    ntotal = 0;
    $('#recebedados input[type="checkbox"]').each(function() {
        if ($(this).is(":checked")) {
           stotal += parseFloat($(this).attr("data-valor"));
           ntotal++;
        }else{
            $(this).parent().parent().clone().appendTo("#items");
            $(this).parent().parent().remove();
        }
    });
    $("#totalselecionado").html(stotal);
    $("#numerocheques").html(ntotal);
}
function ListaCheques(data,div){
    var html = '';           
    //---- Exibe informação
    $.each(data, function( i, val ) {
        var emitentes = '';
        $.each(val.emitentes, function( i2, val2 ) {   
            emitentes += val2.emitente+'<br>';
        });

        html = html + '<tr onclick="seleciona(this)">';
                html = html + '<input type="hidden" name="cheques_codchequerepassecheque[]" value="'+val.codchequerepassecheque+'">';
                html = html + '<td><input type="checkbox" id="codcheque-'+val.codcheque+'" name="cheques_codcheque[]" data-valor="'+val.valor+'" value="'+val.codcheque+'"></td>';
                html = html + '<td width="40"><b>'+val.valor_formatado+'<br> '+val.vencimento+'</b></td>';
                html = html + '<td><a href="'+val.linkpessoa+'"><b>'+val.pessoa+'</b><br></a><span class="text-muted">'+emitentes+'</span></td>';
                html = html + '<td class="text-muted">'+val.banco+'<br>'+val.agencia+'</td>';
                html = html + '<td class="text-right text-muted">'+val.numero+'</td>';
                html = html + '<td class="text-muted"><a href="'+val.linkcheque+'">'+val.codcheque+'</a></td>';
        html = html + '</tr>';

    });
    $("#"+div).html(html);
}
</script>
@endsection