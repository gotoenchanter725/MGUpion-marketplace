{{ Form::value('data') }}
<fieldset class="form-group">
    {!! Form::label('data', 'Data') !!}
    {!! Form::date('data', (!empty($model->data)?formataData($model->data, 'Y-m-d'):null), ['class'=> 'form-control', 'id'=>'data', 'required'=>'required', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('feriado', 'Feriado') !!}
    {!! Form::text('feriado', null, ['class'=> 'form-control', 'id'=>'feriado', 'maxlength'=>'100', 'required'=>'required']) !!}
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
    $("#feriado").Setcase();
    $("#feriado").maxlength({alwaysShow: true});
});
</script>
@endsection