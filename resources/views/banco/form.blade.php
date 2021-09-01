<fieldset class="form-group">
    {!! Form::label('banco', 'Banco') !!}
    {!! Form::text('banco', null, ['class'=> 'form-control', 'id'=>'banco', 'maxlength'=>'50', 'required'=>'required', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('sigla', 'Sigla') !!}
    {!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'maxlength'=>'3']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('numerobanco', 'Número banco') !!}
    {!! Form::number('numerobanco', null, ['class'=> 'form-control', 'id'=>'numerobanco', 'step'=>'1', 'min'=>'1']) !!}
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
    $("#banco").Setcase();
    $("#banco").maxlength({alwaysShow: true});
    $("#sigla").Setcase();
    $("#sigla").maxlength({alwaysShow: true});
});
</script>
@endsection