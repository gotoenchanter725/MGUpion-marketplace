@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">
                Trocar senha
            </h4>
            <div class="card-block">
                @include('errors.form_error')
                <form action="{{ url('usuario/mudar-senha-update') }}" method="post" id="form-usuario">
                    {!! csrf_field() !!}
                    <fieldset class="form-group">
                        {!! Form::label('senhaantiga', 'Senha Antiga') !!}
                        {!! Form::password('senhaantiga', ['id'=>'senhaantiga', 'required'=>'required', 'class'=>'form-control', 'minlength'=>'4']) !!}
                    </fieldset>

                    <fieldset class="form-group">
                        {!! Form::label('senha', 'Nova senha') !!}
                        {!! Form::password('senha', ['id'=>'senha', 'required'=>'required', 'class'=>'form-control', 'minlength'=>'4']) !!}
                    </fieldset>

                    <fieldset class="form-group">
                        {!! Form::label('repetir_senha', 'Confirmação') !!}
                        {!! Form::password('repetir_senha', ['id'=>'repetir_senha', 'required'=>'required', 'class'=>'form-control', 'minlength'=>'4']) !!}
                        <span id="error-rpt"></span>
                    </fieldset>

                    <fieldset class="form-group">
                       {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
                    </fieldset>    
                </form>
            </div>
        </div>
    </div>
</div>
@section('inscript')

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
        swal({
          title: "Confirmação de senha é diferente da senha!",
          type: "error",
          showCancelButton: true,
          showConfirmButton: false,
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          } 
        });  

        } else {
            $("#rpt").empty();
        }
    }    
    
    $("#repetir_senha" ).blur(function() {
      validarSenha();
    });    
    
    $('#form-usuario').on("submit", function(e) {
        if(validarSenha() === false) {
            return false;
        }

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
@stop

