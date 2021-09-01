<fieldset class="form-group">
    {!! Form::label('estoquelocal', 'Estoquelocal') !!}
    {!! Form::text('estoquelocal', null, ['class'=> 'form-control', 'id'=>'estoquelocal', 'maxlength'=>'50', 'required'=>'required', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('codfilial', 'Codfilial') !!}
    {!! Form::number('codfilial', null, ['class'=> 'form-control', 'id'=>'codfilial', 'step'=>'1', 'min'=>'1', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('sigla', 'Sigla') !!}
    {!! Form::text('sigla', null, ['class'=> 'form-control', 'id'=>'sigla', 'maxlength'=>'3', 'required'=>'required']) !!}
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
    $("#estoquelocal").Setcase();
    $("#estoquelocal").maxlength({alwaysShow: true});
    $("#sigla").Setcase();
    $("#sigla").maxlength({alwaysShow: true});
});
</script>
@endsection