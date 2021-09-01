<?php
    $o = shell_exec("lpstat -d -p");
    $res = explode("\n", $o);
    $printers = [];
    foreach ($res as $r) 
    {
        if (strpos($r, "printer") !== FALSE) 
        {
            $r = str_replace("printer ", "", $r);
            $r = explode(" ", $r);
            $printers[$r[0]] = $r[0];
        }
    }
?>
<div class="row">
    <div class="col-md-6">
        <fieldset class="form-group">
            {!! Form::label('usuario', 'Usuário') !!}
            {!! Form::text('usuario', null, ['class'=> 'form-control', 'id'=>'usuario', 'required'=>'required']) !!}
        </fieldset>
<!--
        <fieldset class="form-group">
            {!! Form::label('senha', 'Senha') !!}
            {!! Form::password('senha', ['class'=> 'form-control', 'id'=>'senha', isset($model) ? null : 'required=required', 'minlength'=>'6' ]) !!}
        </fieldset>

        <fieldset class="form-group">
            {!! Form::label('repetir_senha', 'Confirmação') !!}
            {!! Form::password('repetir_senha', ['class'=> 'form-control', 'id'=>'repetir_senha', isset($model) ? null : 'required=required']) !!}
                <span id="error-rpt"></span>
        </fieldset>
-->
        <fieldset class="form-group">
            {!! Form::label('codfilial', 'Filial') !!}
            {!! Form::select2Filial('codfilial', null, ['somenteAtivos' => true,'class' => 'form-control', 'id'=>'codfilial']) !!}
        </fieldset>
        
        <fieldset class="form-group">
            {!! Form::label('codpessoa', 'Pessoa') !!}
            {!! Form::select2Pessoa('codpessoa', null, ['class' => 'form-control', 'id'=>'codpessoa', 'placeholder' => 'Pessoa', 'ativo' => 1]) !!}
        </fieldset>
    </div>

    <div class="col-md-6">
        <fieldset class="form-group">
            {!! Form::label('impressoramatricial', 'Impressora Matricial') !!}  
            {!! Form::select2('impressoramatricial', $printers, null, ['class'=> 'form-control', 'id'=>'impressoramatricial','required'=>'required', 'placeholder' => 'Impressora Matricial']) !!}
        </fieldset>

        <fieldset class="form-group">
            {!! Form::label('impressoratermica', 'Impressora Térmica') !!}  
            {!! Form::select2('impressoratermica', $printers, null, ['class' => 'form-control', 'id'=>'impressoratermica','required'=>'required', 'placeholder' => 'Impressora Termica']) !!}
        </fieldset>

        <fieldset class="form-group">
            {!! Form::label('codportador', 'Portador') !!}  
            {!! Form::select2Portador('codportador', null, ['class' => 'form-control', 'id'=>'codportador']) !!}
        </fieldset>

        <fieldset class="form-group">
           {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
        </fieldset>    
    </div>
</div>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<style type="text/css">
    #error-rpt {
        float:right;
        color: #b94a48;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
    function validarSenha(form){ 
        senha = $('#senha').val();
        senhaRepetida = $('#repetir_senha').val();
        if (senha != senhaRepetida){
            var aviso = '<span id="rpt">Confirmação deve ser exatamente repetido</span>';
            var destino = $('#error-rpt');
            destino.append(aviso);
            setTimeout(function(){
                $('#rpt').remove();
            },13000);            
            $('repetir_senha').focus();
            console.log(senha + '-' + senhaRepetida);
            return false;
        } else {
            $("#rpt").remove();
        }
    }    
    $("#repetir_senha" ).blur(function() {
      validarSenha();
    });    

    $('#form-usuario').on("submit", function(e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja salvar?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          } 
        }); 
    });

});
</script>
@endsection
