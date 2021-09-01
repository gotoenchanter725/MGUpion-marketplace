{!! Form::hidden('codproduto', Request::get('codproduto')) !!}    
<fieldset class="form-group">
    {!! Form::label('variacao', 'Variaçao') !!}
    {!! Form::text('variacao', null, ['class'=> 'form-control', 'id'=>'variacao', 'required'=>'true', 'maxlength'=>'100', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('codmarca', 'Marca') !!}
    {!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('referencia', 'Referencia') !!}
    {!! Form::text('referencia', null, ['class'=> 'form-control', 'id'=>'referencia']) !!}
</fieldset>

<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#variacao").Setcase();
    $("#variacao").maxlength({alwaysShow: true});
    
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
     
});
</script>
@endsection
