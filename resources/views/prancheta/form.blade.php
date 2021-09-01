<fieldset class="form-group">
    {!! Form::label('prancheta', 'Prancheta') !!}
    {!! Form::text('prancheta', null, ['class'=> 'form-control', 'id'=>'prancheta', 'maxlength'=>'50', 'required'=>'required', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('observacoes', 'Observacoes') !!}
    {!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'maxlength'=>'200']) !!}
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
    $("#prancheta").Setcase();
    $("#prancheta").maxlength({alwaysShow: true});
    $("#observacoes").maxlength({alwaysShow: true});
});
</script>
@endsection