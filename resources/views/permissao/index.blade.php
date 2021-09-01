@extends('layouts.default')
@section('content')

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

@section('inscript')
<script type="text/javascript">
    
    function create(element, classe, metodo, codgrupousuario) {
        $.ajax({
            type: 'POST',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ url('permissao') }}',
            dataType: 'json',
            data: {
                classe: classe,
                metodo: metodo,
                codgrupousuario: codgrupousuario,
            },
            success: function(data) {
                element.prop('checked', true);
                toastr['success']('Permissão <B>' + data.permissao + '</B> para <B>' + data.grupousuario + '</B> criada!');
            },
            error: function(XHR) {
                element.prop('checked', false);
                toastr['error'](XHR.status + ' ' + XHR.statusText);
            },
        });
    }
    
    function destroy(element, classe, metodo, codgrupousuario) {
        $.ajax({
            type: 'DELETE',
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ url('permissao') }}',
            dataType: 'json',
            data: {
                classe: classe,
                metodo: metodo,
                codgrupousuario: codgrupousuario,
            },
            success: function(data) {
                element.prop('checked', false);
                toastr['warning']('Permissão <B>' + data.permissao + '</B> para <B>' + data.grupousuario + '</B> excluída!');
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
            var classe = $(this).data('classe');
            var metodo = $(this).data('metodo');
            var codgrupousuario = $(this).data('codgrupousuario');
            if ($(this).is(":checked")) {
                create($(this), classe, metodo, codgrupousuario)
            } else {
                destroy($(this), classe, metodo, codgrupousuario)
            }
        });
    });
</script>
@endsection

@stop