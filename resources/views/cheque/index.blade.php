@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-4">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">#</label>
                            {!! Form::number('codcheque', null, ['class' => 'form-control text-right', 'placeholder' => '#', 'step'=>1, 'min'=>1, 'id' => 'codcheque']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Banco</label>
                            {!! Form::select2Banco('codbanco', null, ['class'=> 'form-control', 'id' => 'codbanco']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Agência</label>
                            {!! Form::number('agencia', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'agencia']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Número</label>
                            {!! Form::number('numero', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'numero']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Pessoa</label>
                            {!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'placeholder' => 'Pessoa']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Emitente</label>
                            {!! Form::text('emitente', null, ['class' => 'form-control', 'placeholder' => 'Emitente', 'id' => 'emitente']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Valor de</label>
                            {!! Form::number('valor_de', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'valor_de']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Valor até</label>
                            {!! Form::number('valor_ate', null, ['class' => 'form-control', 'step' => 1, 'placeholder' => '', 'id' => 'valor_ate']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="control-label">Status</label>
                            {!! Form::select2('indstatus',  $status, ['class'=> 'form-control', 'id'=>'indstatus']) !!}
                       </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Vencimento De</label>
                            {!! Form::date('vencimento_de', null, ['class'=> 'form-control', 'id' => 'vencimento_de']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label">Até</label>
                            {!! Form::date('vencimento_ate', null, ['class'=> 'form-control', 'id' => 'vencimento_ate']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inativo" class="control-label">Ativos</label>
                            {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                        </div>
                    </div>
                </div>
            
                <div class="clearfix"></div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>
<div id="caixa-selecao" class="rodape-selecao">
    <div class="rodape-selecao-caixa">
        <span>Cheques Selecionados</span>
        <strong id="nselecionados">1</strong>
    </div>
    <div class="rodape-selecao-caixa">
        <span>Total</span>
        <strong id="totalselecionado">R$ 00,00</strong>
    </div>
    <div class="rodape-selecao-caixa rodape-selecao-caixa-form">
        <select id="statusalterar" class="form-control">
            <option value="3">Devolvido</option>
            <option value="5">Liquidado</option>
        </select>
    </div>
    <div class="rodape-selecao-caixa rodape-selecao-caixa-form">
        <button id="enviar-status" class="btn btn-success">Enviar <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>
    </div>
</div>
<div class='card'>
    <div class='card-block table-responsive'>
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde','-', '#', 'Banco', 'Agencia', 'Contacorrente', 'Numero', 'Pessoa', 'Emitentes', 'Valor', 'Data Emissão', 'Data Vencimento', 'Status' ]])
        <div class='clearfix'></div>
    </div>
</div>


<div class="modal fade" id="modal-devolucao">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Devolução</h5>
            </div>
            <form id="form-devolucao">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            {!! Form::label('codchequemotivodevolucao', 'Motivo devolução:', []) !!}
                            {!! Form::select2Chequemotivodevolucao('codchequemotivodevolucao', null, ['class'=> 'form-control', 'id'=>'codchequemotivodevolucao', 'placeholder'=>'Cliente', 'required'=>'required']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('datadevolucao', 'Data de Devolução', []) !!}
                            {!! Form::date('datadevolucao', date('Y-m-d'), ['class'=> 'form-control', 'id'=>'datadevolucao', 'required'=>'required']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('observacao', 'Observação:', []) !!}
                            {!! Form::textarea('observacao', null, ['class'=> 'form-control', 'rows'=>'3', 'id'=>'observacao']) !!}
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Salvar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("cheque/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('cheque/datatable'), 'order' => $filtro['order'], 'filtros' => ['codcheque', 'inativo', 'codbanco', 'agencia', 'contacorrente', 'emitente', 'numero', 'valor_de', 'valor_ate', 'vencimento_de', 'vencimento_ate', 'indstatus'] ])
    <style>
        .rodape-selecao{
            float:left;
            position:fixed;
            bottom:0px;
            right:0;
            left:72px;
            width:100%;
            height:50px;
            z-index: 200;
            background:#fff;
            -webkit-box-shadow: 2px -1px 5px 0px rgba(0,0,0,0.21);
            -moz-box-shadow: 2px -1px 5px 0px rgba(0,0,0,0.21);
            box-shadow: 2px -1px 5px 0px rgba(0,0,0,0.21);
        }
        .rodape-selecao-caixa{
            float:left;
            width:200px;
            padding:5px;
        }
        .rodape-selecao-caixa span{
            font-size:12px;
            float:left;
            width:100%;
        }
        .rodape-selecao-caixa strong{
            font-size:16px;
            float:left;
            width:100%;
        }
        .rodape-selecao-caixa-form{
            padding-top: 10px;
        }
        #caixa-selecao{ 
            display:none; 
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            
            $("#enviar-status").click(function() {

                var statusalterar = $("#statusalterar").val();

                if(statusalterar==''){
                    swal({
                       title: "Status Vazio",
                       type: "warning",
                       showCancelButton: true,
                       closeOnConfirm: false,
                       closeOnCancel: true
                    });
                    return false;
                }
                if(statusalterar==3){
                    $('#modal-devolucao').modal('show');
                }
            });
            //----- Envia devolução
            $('#form-devolucao').on("submit", function(e) {
                
                var codchequemotivodevolucao = $("#codchequemotivodevolucao").val();
                var datadevolucao = $("#datadevolucao").val();
                var observacao = $("#observacao").val();
                var cheques = $("#observacao").val();
                
                $.ajax({
                    type: 'POST',
                    url: '{{ url('cheque/devolucao') }}/',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {'codchequemotivodevolucao':codchequemotivodevolucao,'data':datadevolucao,'observacao':observacao,'cheques':cheques},
                    success: function(retorno) {
                        
                        console.log(retorno);
                       
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
            
            //-----| Valor
            var valor_de = $('input[name=valor_de]').val();
            if(valor_de.length > 0 ){
                $('input[name=valor_ate]').attr('min', valor_de);
            }

            var valor_ate = $('input[name=valor_ate]').val();
            if(valor_de.length > 0 ){
                $('input[name=valor_de]').attr('min', valor_ate);
            }

            $('input[name=valor_de]').on('change', function(e) {
                e.preventDefault();
                setValorMin();
            }).blur(function () {
                setValorMin();
            });

            $('input[name=valor_ate]').on('change', function(e) {
                e.preventDefault();
                setValorMax();
            }).blur(function () {
                setValorMax();
            });

            //----- Data

            var vencimento_de = $('input[name=vencimento_de]').val();
            if(vencimento_de.length > 0 ){
                $('input[name=vencimento_ate]').attr('min', vencimento_de);
            }
            $('input[name=vencimento_de]').on('change', function(e) {
                e.preventDefault();
                var valor = $(this).val();
                if(valor.length === 0 ) {
                    $('input[name=vencimento_ate]').empty();
                    $('input[name=vencimento_ate]').attr('min', '');
                } else {
                    $('input[name=vencimento_ate]').attr('min', valor);
                }

            });

            var vencimento_ate = $('input[name=vencimento_ate]').val();
            if(vencimento_ate.length > 0){
                $('input[name=vencimento_de]').attr('max', vencimento_ate);
            }
            $('input[name=vencimento_ate]').on('change', function(e) {
                e.preventDefault();
                var valor = $(this).val();
                if(valor.length === 0 ) {
                    $('input[name=vencimento_de]').empty();
                    $('input[name=vencimento_de]').attr('max', '');
                } else {
                    $('input[name=vencimento_de]').attr('max', valor);
                }
            });
        });

        function setValorMin() {
            var valor = $('input[name=valor_de]').val();
            if(valor.length === 0 ) {
                $('input[name=valor_ate]').empty();
                $('input[name=valor_ate]').attr('min', '');
            } else {
                $('input[name=valor_ate]').attr('min', valor);
            }
        };

        function setValorMax() {
            var valor_de = $('input[name=valor_de]').val();
            var preco_ate = $('input[name=valor_ate]').val();
            if(valor_de.length === 0 ) {
                $('input[name=valor_de]').attr('max', preco_ate);
            }
        };
        
        //----- Cheque Seleciona
        function chequeseleciona(){
            somatotal();
        }
        
        function somatotal(){
            stotal = 0;
            ntotal = 0;
            $('#datatable input[type="checkbox"]').each(function() {
                if($(this).is(":checked")) {
                    stotal += parseFloat($(this).attr("data-valor"));
                    ntotal++;
                    $(this).parent().parent().parent().addClass("table-success");
                }else{
                    $(this).parent().parent().parent().removeClass("table-success");
                }
            });
            if(ntotal>0){
                $("#caixa-selecao").show();
                $("#nselecionados").html(ntotal);
                $("#totalselecionado").html('R$ '+stotal);
            }else{
                $("#caixa-selecao").hide();
            }
        }
    </script>

@endsection
@stop
