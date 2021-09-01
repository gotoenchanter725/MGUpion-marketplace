@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card ">
      <h4 class="card-header">Parâmetros</h4>
      <div class="card-block">
        {!! Form::model($parametros, ['id' => 'form-parametros', 'autocomplete' => 'on'])!!}
        <div class='row'>
          <div class='col-md-6'>
            <fieldset class="form-group">
              {!! Form::label('data_inicial', 'De') !!}
              {!! Form::date('data_inicial', $parametros['data_inicial']->format('Y-m-d'), ['class'=> 'form-control text-right', 'id'=>'data_inicial', 'required'=>'required', 'autofocus']) !!}
            </fieldset>
          </div>
          <div class='col-md-6'>
            <fieldset class="form-group">
              {!! Form::label('data_final', 'Até') !!}
              {!! Form::date('data_final', $parametros['data_final']->format('Y-m-d'), ['class'=> 'form-control', 'id'=>'data_final', 'required'=>'required']) !!}
            </fieldset>
          </div>
        </div>
        <fieldset class="form-group">
          {!! Form::label('codfilial', 'Filial') !!}
          {!! Form::select2Filial('codfilial', null, ['class'=> 'form-control', 'id'=>'codfilial', 'required'=>'required']) !!}
        </fieldset>
        <fieldset class="form-group">
          {!! Form::label('arquivo_estoque', 'Arquivo de Saldos de Estoque') !!}
          <div class='row'>
            <div class='col-md-9'>
              {!! Form::text('arquivo_estoque', null, ['class'=> 'form-control text-right', 'id'=>'arquivo_estoque']) !!}
            </div>
            <div class='col-md-3'>
              {!! Form::button('Exportar', array('id' => 'btnExportaEstoque', 'class' => 'btn btn-primary col-md-12')) !!}
            </div>
              
          </div>
        </fieldset>
        <fieldset class="form-group">
        </fieldset>    
        {!! Form::close() !!}
      </div>
    </div>
  </div>
  
  <div class="col-md-8">
    <div class="card ">
      <h4 class="card-header">Histórico</h4>
      <div class="card-block">
        <pre id='log'></pre>
      </div>
    </div>
  </div>
  
</div>
<?php /*
<div class="row">
<div class="col-md-3">
  <div class="card ">
    <h4 class="card-header">Policies</h4>
    <div class="card-block">
      <ul class="nav nav-pills nav-stacked m-b-10" id="myTabalt" role="tablist">
        @foreach ($classes as $classe => $metodos)
          <li class="nav-item">
            <a class="nav-link" id="{{ $classe }}-tab1" data-toggle="tab" href="#{{ $classe }}-tab" role="tab" aria-controls="{{ $classe }}" aria-expanded="true">{{ $classe }}</a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
<div class="tab-content" id="myTabaltContent">
  @foreach ($classes as $classe => $metodos)
    <div class="tab-pane fade in" id="{{ $classe }}-tab" aria-labelledby="{{ $classe }}-tab">
      <div class="col-md-9" role="tabpanel" >
        <div class="card">
          <h4 class="card-header">Permissões de <i>'{{$classe}}'</i></h4>
          <div class='card-block'>
          <table class="table table-sm table-striped table-hover table-responsive">
            <thead>
              <tr>
                <th>
                  &nbsp;
                </th>
                @foreach ($grupos as $grupo)
                  <th>
                    <a href='{{ url('grupo-usuario', $grupo->codgrupousuario) }}'>
                      {{ $grupo->grupousuario }}
                    </a>
                  </th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach ($metodos as $metodo => $codpermissao)
                <tr>
                  <th>
                    {{ $metodo }}
                  </th>
                  @foreach ($grupos as $grupo)
                    <td class="text-right" style="text-align: center">
                      {!! 
                        Form::checkbox(
                            "permissao_{$classe}_{$metodo}_{$grupo->codgrupousuario}", 
                            null, 
                            !empty($permissoes[$grupo->codgrupousuario][$classes[$classe][$metodo]]), 
                            [
                                'class' => 'form-control permissao', 
                                'id' => "permissao_{$classe}_{$metodo}_{$grupo->codgrupousuario}",
                                'data-classe' => $classe,
                                'data-metodo' => $metodo,
                                'data-codgrupousuario' => $grupo->codgrupousuario,
                            ]
                        ); !!}
                    </td>
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>
<div class="clearfix"></div>
</div>
*/?>
               
@section('inscript')
<script type="text/javascript">

function validaFilial() {
    if ($('#codfilial').val() == '') {
        swal({
            title: 'Selecione a Filial!',
            type: 'error',
        }, function () {
            $('#codfilial').select2('open');
        });
        return false;
    }        
    return true;
}

function valida(campo, mensagem) {
    if ($(campo).val() == '') {
        swal({
            title: mensagem,
            type: 'error',
        }, function () {
            $(campo).focus();
        });
        return false;
    }        
    return true;
    
}

function validaDataInicial() {
    return valida('#data_inicial', 'Informe a Data Inicial!');
}

function validaDataFinal() {
    return valida('#data_final', 'Informe a Data Final!');
}

function validaArquivoEstoque() {
    return valida('#arquivo_estoque', 'Informe o nome do Arquivo de Estoque');
}

function log(texto) {
    $('#log').append(texto + '\n');
}

function exportarEstoque () {
    
    //Faz chamada Ajax
    $.ajax({
        type: 'POST',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            data: $('#data_final').val(),
            codfilial: $('#codfilial').val(),
            arquivo_estoque: $('#arquivo_estoque').val(),
        },
        url: '{{ url('dominio/exporta-estoque') }}',
        dataType: 'json',

        // Caso veio retorno
        success: function(retorno) {

            // Se executou
            if (retorno.OK) {
                swal({
                    title: 'Sucesso!',
                    text: 'Operação efetuada com sucesso!',
                    type: 'success',
                });
                log('Estoque: ' + retorno.OK);

            // Se não executou
            } else {
                swal({
                    title: 'Erro!',
                    text: retorno.mensagem,
                    type: 'error',
                });
                log('Estoque: ' + retorno.mensagem);
            }

        },

        // Caso Erro
        error: function (XHR) {

            if(XHR.status === 403) {
                var title = 'Permissão Negada!';
            } else {
               var title = 'Falha na execução!';
            }

            swal({
                title: title,
                text: XHR.status + ' ' + XHR.statusText,
                type: 'error',
            });
            log('Estoque: ' + XHR.statusText);
        }
    }); 
}

$(document).ready(function () {

    $('#btnExportaEstoque').click(function (e) {
        
        if (!validaDataInicial()) { return false; }
        if (!validaFilial()) { return false; }
        if (!validaArquivoEstoque()) { return false; }
        
        swal({
            title: "Gerar arquivo de Estoque?",
            text: "Tem certeza que deseja gerar o arquivo com os saldos de estoque?",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        },
        function(){
           exportarEstoque();
        });        
        
    });
    
});
</script>
@endsection

@stop