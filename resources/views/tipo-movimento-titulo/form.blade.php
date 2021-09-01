<fieldset class="form-group">
    {!! Form::label('tipomovimentotitulo', 'Tipomovimentotitulo') !!}
    {!! Form::text('tipomovimentotitulo', null, ['class'=> 'form-control', 'id'=>'tipomovimentotitulo', 'maxlength'=>'20', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('implantacao', 'Implantacao') !!}
    {!! Form::checkbox('implantacao', null, ['class'=> 'form-control', 'id'=>'implantacao', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('ajuste', 'Ajuste') !!}
    {!! Form::checkbox('ajuste', null, ['class'=> 'form-control', 'id'=>'ajuste', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('armotizacao', 'Armotizacao') !!}
    {!! Form::checkbox('armotizacao', null, ['class'=> 'form-control', 'id'=>'armotizacao', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('juros', 'Juros') !!}
    {!! Form::checkbox('juros', null, ['class'=> 'form-control', 'id'=>'juros', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('desconto', 'Desconto') !!}
    {!! Form::checkbox('desconto', null, ['class'=> 'form-control', 'id'=>'desconto', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('pagamento', 'Pagamento') !!}
    {!! Form::checkbox('pagamento', null, ['class'=> 'form-control', 'id'=>'pagamento', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('estorno', 'Estorno') !!}
    {!! Form::checkbox('estorno', null, ['class'=> 'form-control', 'id'=>'estorno', 'required'=>'required']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('observacao', 'Observacao') !!}
    {!! Form::text('observacao', null, ['class'=> 'form-control', 'id'=>'observacao', 'maxlength'=>'255']) !!}
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
    $("#tipomovimentotitulo").Setcase();
    $("#tipomovimentotitulo").maxlength({alwaysShow: true});
    $("#observacao").Setcase();
    $("#observacao").maxlength({alwaysShow: true});
});
</script>
@endsection