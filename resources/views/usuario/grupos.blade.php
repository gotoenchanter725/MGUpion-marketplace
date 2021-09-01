@extends('layouts.default')
@section('content')
<div class='row'>
  <div class="col-md-12">
    <div class="card">
      <h3 class="card-header">
        Grupos do usuário <i>'{{$model->usuario}}'</i>
      </h3>
      <div class="card-block">
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
            @foreach ($filiais as $filial)
              <tr>
                <th>
                  {{ $filial->filial }}
                </th>
                @foreach ($grupos as $grupo)
                  <td class="text-right" style="text-align: center">
                    {!! 
                      Form::checkbox(
                          "permissao_{$grupo->codgrupousuario}_{$filial->codfilial}", 
                          null, 
                          !empty($grupos_usuario[$grupo->codgrupousuario][$filial->codfilial]), 
                          [
                              'class' => 'form-control permissao', 
                              'id' => "permissao_{$grupo->codgrupousuario}_{$filial->codfilial}",
                              'data-codgrupousuario' => $grupo->codgrupousuario,
                              'data-codfilial' => $filial->codfilial,
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

@section('inscript')
<script type="text/javascript">
    function create(element, codgrupousuario, codfilial) {
        $.ajax({
            type: 'POST',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ url("usuario/{$model->codusuario}/grupos") }}',
            dataType: 'json',
            data: {
                codgrupousuario: codgrupousuario,
                codfilial: codfilial,
            },
            success: function(data) {
                element.prop('checked', true);
                toastr['success']('Usuário associado ao grupo <B>' + data.grupousuario + '</B> para <B>' + data.filial + '</B>!');
            },
            error: function(XHR) {
                element.prop('checked', false);
                if(XHR.status === 403){
                    toastr['error']('<strong>Acesso negado!</strong> Você não tem permissão para essa ação');
                } else {
                    toastr['error'](XHR.status + ' ' + XHR.statusText);
                }
            },
        });
    }
    
    function destroy(element, codgrupousuario, codfilial) {
        $.ajax({
            type: 'DELETE',
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ url("usuario/{$model->codusuario}/grupos") }}',
            dataType: 'json',
            data: {
                codgrupousuario: codgrupousuario,
                codfilial: codfilial,
            },
            success: function(data) {
                element.prop('checked', false);
                toastr['warning']('Usuário retirado do grupo <B>' + data.grupousuario + '</B> para <B>' + data.filial + '</B>!');
            },
            error: function(XHR) {
                element.prop('checked', true);
                toastr['error'](XHR.status + ' ' + XHR.statusText);
            },
        });
    }
    
    $(document).ready(function () {
        
        $('.permissao').click(function(e) {
            e.preventDefault();
            var id = $(this).prop('id');
            var codgrupousuario = $(this).data('codgrupousuario');
            var codfilial = $(this).data('codfilial');
            if ($(this).is(":checked")) {
                create($(this), codgrupousuario, codfilial)
            } else {
                destroy($(this), codgrupousuario, codfilial)
            }
        });
    });
</script>
@endsection
@stop