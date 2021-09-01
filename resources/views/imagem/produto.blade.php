@extends('layouts.default')
@section('content')
<script src="{{ URL::asset('public/assets/plugins/slim-image-cropper/slim.kickstart.min.js') }}"></script>
<link href="{{ URL::asset('public/assets/plugins/slim-image-cropper/slim.min.css') }}" rel="stylesheet">

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-header">
                Novo
            </h4>
            <div class="card-block">
                @include('errors.form_error')
                {!! Form::model($model, [
                    'method' => 'POST', 
                    'class' => 'form-horizontal', 
                    'id' => 'form-principal', 
                    'route' => ['imagem.store', 'model' => Request::get('model'), 'id' => Request::get('id'), 'codimagem'=>Request::get('codimagem')],
                    'files'=>true
                ]) !!}

                <div class="form-group">
                    <label for="imagem">
                        {!! Form::label('Imagem: ') !!}
                    </label>    
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
                            style='max-width: 500px'
                            > 
                            <input type="file" />
                    </div>
                </div>
                <fieldset class="form-group">
                   {!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}
                </fieldset>
                {!! Form::close() !!}
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
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
    $("#observacoes").Setcase();
    $("#observacoes").maxlength({alwaysShow: true});
});
</script>
@endsection
@stop