
<div class="card">
  <h3 class="card-header">
    View Index
    <div class="btn-group " role="group" aria-label="Controles">
        <a class="btn btn-secondary btn-sm" id="btn-salvar-view-index"><i class="fa fa-save"></i></a> 
    </div>
  </h3>
  <div class="card-block">
    
    <pre class=""><code><?php 
        highlight_string($conteudo);
    ?></code></pre>
  </div>
</div>

<script type="text/javascript">
    
$(document).ready(function () {

    $('#btn-salvar-view-index').click(function () {

    // Pergunta
    swal({
      title: 'Tem certeza que deseja salvar o arquivo de View Index gerado?',
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
                url: '{{ url("gerador-codigo/$tabela/view/index") }}',
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
