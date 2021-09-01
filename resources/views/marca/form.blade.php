<fieldset class="form-group">
    {!! Form::label('marca', 'Marca') !!}
    {!! Form::text('marca', null, ['class'=> 'form-control', 'id'=>'marca', 'required'=>'required', 'autofocus']) !!}
</fieldset>

<fieldset class="form-group">
    <div class="checkbox checkbox-primary">
    {!! Form::checkbox('site', true, null, ['class'=> 'form-control', 'id'=>'site']) !!}
    {!! Form::label('site', 'Disponível no Site') !!}
    </div>
</fieldset>

<fieldset class="form-group">
    {!! Form::label('descricaosite', 'Descrição no Site') !!}
    {!! Form::textarea('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite']) !!}
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
    $("#marca").Setcase();
});
</script>
@endsection
