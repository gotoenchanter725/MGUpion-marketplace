{!! Form::hidden('codproduto', Request::get('codproduto')) !!} 
<fieldset class="form-group">
    {!! Form::label('codunidademedida', 'Unidade de Medida') !!}
    {!! Form::select2UnidadeMedida('codunidademedida', null, ['required' => true,  'class'=> 'form-control', 'campo' => 'unidademedida', 'id' => 'codunidademedida']) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('quantidade', 'Quantidade') !!}
    {!! Form::number('quantidade', null, ['class'=> 'form-control', 'id'=>'quantidade', 'step'=>'0.001', 'min'=>'2', 'required'=>true]) !!}
</fieldset>
<fieldset class="form-group">
    {!! Form::label('preco', 'Preco') !!}
    {!! Form::number('preco', null, ['class'=> 'form-control', 'id'=>'preco', 'step'=>'0.01', 'min'=>'2']) !!}
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
});
</script>
@endsection