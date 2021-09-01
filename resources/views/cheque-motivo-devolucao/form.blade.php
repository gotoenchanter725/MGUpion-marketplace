<fieldset class="form-group">
    {!! Form::label('numero', 'Numero') !!}
    {!! Form::number('numero', null, ['class'=> 'form-control', 'id'=>'numero', 'step'=>'1', 'min'=>'1', 'required'=>'required', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('chequemotivodevolucao', 'Chequemotivodevolucao') !!}
    {!! Form::text('chequemotivodevolucao', null, ['class'=> 'form-control', 'id'=>'chequemotivodevolucao', 'maxlength'=>'200', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
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
    $("#chequemotivodevolucao").Setcase();
    $("#chequemotivodevolucao").maxlength({alwaysShow: true});
});
</script>
@endsection