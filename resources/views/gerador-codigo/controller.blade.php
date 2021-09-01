
<div class="card">
  <h3 class="card-header">
    Arquivo de Controller
    <div class="btn-group " role="group" aria-label="Controles">
        <a class="btn btn-secondary btn-sm" id="btn-salvar-controller"><i class="fa fa-save"></i></a> 
    </div>
  </h3>
  <div class="card-block">
    
    @if ($registrado)
        <strong class='text-success'>Rota Registrada!</strong> 
    @else
        <strong class='text-danger'>Falta Registrar a Rota!</strong> 
    @endif
    <br>
    <code>{{ $string_registro }}</code>
    
    <pre class=""><code><?php 
        highlight_string($conteudo);
    ?></code></pre>
  </div>
</div>

<script type="text/javascript">
    
$(document).ready(function () {

    $('#btn-salvar-controller').click(function () {

    // Pergunta
    swal({
      title: 'Tem certeza que deseja salvar o arquivo de Controller gerado?',
      type: "warning",
      showCancelButton: true,
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm) {
        
        // Se confirmou
        if (isConfirm) {
            
            //Faz chamada Ajax
            $.ajax({
                type: 'POST',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url("gerador-codigo/$tabela/controller") }}',
                data: {
                    tabela: '{{ $tabela }}',
                    model: '{{ $model }}',
                    titulo: '{{ $titulo }}',
                    coluna_titulo: '{{ $coluna_titulo }}',
                    url: '{{ $url }}',
                },
                dataType: 'json',
                
                // Caso veio retorno
                success: function(retorno) {
                    
                    // Se executou
                    if (retorno.OK) {
                        swal({
                            title: 'Sucesso!',
                            text: 'Operação efetuada com sucesso!',
                            type: 'success',
                        }, function () {
                            if (typeof after !== 'undefined') {
                                eval(after);
                            }
                        });
                        
                    // Se não executou
                    } else {
                        swal({
                            title: 'Erro!',
                            text: retorno.mensagem,
                            type: 'error',
                        }, function () {
                            if (typeof on_error !== 'undefined') {
                                eval(on_error);
                            }
                        });
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
                    }, function () {
                        if (typeof on_error !== 'undefined') {
                            eval(on_error);
                        }
                    });
                }
            });        
        } 
    });     
    
    return true;
    });

});
</script>
