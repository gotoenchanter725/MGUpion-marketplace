<fieldset class="form-group">
    {!! Form::label('unidademedida', 'Descrição') !!}
    {!! Form::text('unidademedida', null, ['class'=> 'form-control', 'id'=>'unidademedida', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('sigla', 'Sigla') !!}
    {!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'required'=>'required', 'maxlength'=>'3', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
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
    $("#unidademedida").Setcase();  
});
</script>
@endsection