<fieldset class="form-group">
    {!! Form::label('grupousuario', 'Grupo de usuário') !!}
  	{!! Form::text('grupousuario', null, ['class'=> 'form-control', 'id'=>'grupousuario', 'required'=>'required', 'autofocus']) !!}
</fieldset>

<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#form-grupo-usuario').on("submit", function(e) {
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

    $('#grupousuario').Setcase();     
});
</script>
@endsection