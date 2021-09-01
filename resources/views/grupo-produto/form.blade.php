<fieldset class="form-group">
    {!! Form::label('grupoproduto', 'Grupo Produto') !!}
    {!! Form::text('grupoproduto', null, ['class'=> 'form-control', 'id'=>'grupoproduto', 'required'=>'required', 'autofocus']) !!}
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
    $("#grupoproduto").Setcase();  
});
</script>
@endsection