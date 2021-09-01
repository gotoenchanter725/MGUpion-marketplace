{!! Form::hidden('codproduto', $opt['codproduto']??null) !!}
{!! Form::hidden('codsecaoproduto', $opt['codsecaoproduto']??null) !!}
{!! Form::hidden('codfamiliaproduto', $opt['codfamiliaproduto']??null) !!}
{!! Form::hidden('codgrupoproduto', $opt['codgrupoproduto']??null) !!}
{!! Form::hidden('codsubgrupoproduto', $opt['codsubgrupoproduto']??null) !!}
{!! Form::hidden('codmarca', $opt['codmarca']??null) !!}

<fieldset class="form-group">
    {!! Form::label('imagem', 'Imagem') !!}
    <div 
        class="slim" 
        id="my-cropper"
        data-label="Arraste a Imagem..." 
        data-ratio="1:1" 
        data-size="1536,1536" 
        data-status-upload-success="Imagem Salva..." 
        data-force-type="jpg" 
        data-did-upload="imageUpload"
        data-button-edit-label="Editar"
        data-button-remove-label="Descartar"
        data-button-download-label="Baixar"
        data-button-upload-label="Salvar"
        data-button-cancel-label="Cancelar"
        data-button-confirm-label="Confirmar"
        data-button-edit-title="Editar"
        data-button-remove-title="Descartar"
        data-button-download-title="Baixar"
        data-button-upload-title="Salvar"
        data-button-rotate-title="Girar"
        data-button-cancel-title="Cancelar"
        data-button-confirm-title="Confirmar"
        > 
        <input type="file" id="imagem" />
    </div>
</fieldset>
<fieldset class="form-group">
    {!! Form::label('observacoes', 'Observacoes') !!}
    {!! Form::text('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'maxlength'=>'200', 'autofocus']) !!}
</fieldset>
<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@section('inscript')
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/slim-image-cropper/slim.kickstart.min.js') }}"></script>
<link href="{{ URL::asset('public/assets/plugins/slim-image-cropper/slim.min.css') }}" rel="stylesheet">
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
    $("#observacoes").Setcase();
    $("#observacoes").maxlength({alwaysShow: true});
    $("#arquivo").Setcase();
    $("#arquivo").maxlength({alwaysShow: true});
});
</script>
@endsection