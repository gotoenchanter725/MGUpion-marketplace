$.fn.select2.defaults.set( "theme", "bootstrap" );
function formataCodigo(numero) {
    if (numero > 99999999) {
        return numero;
    }

    numero = new String("00000000" + numero);
    numero = numero.substring(numero.length-8, numero.length);
    return numero;
}

function formataCnpjCpf(numero) {
    //CNPJ
    if (numero > 99999999999) {
        numero = new String("00000000000000" + numero);
        numero = numero.substring(numero.length-14, numero.length);
        // 01 234 567 8901 23
        // 04.576.775/0001-60
        numero = numero.substring(0, 2) 
                 + "."
                 + numero.substring(2, 5)
                 + "."
                 + numero.substring(5, 8)
                 + "/"
                 + numero.substring(8, 12)
                 + "-"
                 + numero.substring(12, 14)
                 ;
    } else { //CPF
        numero = "000000000000" + numero;
        numero = numero.substring(numero.length-11, numero.length);
        // 012 345 678 90
        // 123 456 789 01
        // 803.452.710.68
        numero = numero.substring(0, 3) 
                 + "."
                 + numero.substring(3, 6)
                 + "."
                 + numero.substring(6, 9)
                 + "-"
                 + numero.substring(9, 11)
                 ;
    }

    return numero;
}

function recarregaDiv(div, url) {
    if(url === undefined) {
        url = $(location).attr('href');
    };

    if (url.indexOf("?") == -1)
        url += '?';
    else
        url += '&';
    
    url += '_div=' + div + ' #' + div + ' > *';

    $('#' + div).load(url, function (){
        inicializa('#' + div + ' *');
    });
}

function recarregaDivS(divs, url) {
    if (url === undefined) {
        url = $(location).attr('href');
    };
    
    if (!$.isArray(divs)) {
        divs = [divs];
        
        if (url.indexOf("?") == -1) {
            url += '?';
        } else {
            url += '&';
        }

        url += '_div=' + divs + ' #' + divs + ' > *';
    }

    $.get(url).done(function (html) {
        var newDom = $(html);
        $.each(divs, function (i, div) {
            $('#'+div).replaceWith($('#'+div, newDom));
            inicializa('#' + div + ' *');
        });
    });
}

function deleteActivate (type, method, url, question, after, on_error) {
 
    // Executa Pergunta
    swal({
      title: question,
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
                type: type,
                method: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
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
}


function deleteClick(tag) {
    
    var url = $(tag).attr('href');
    var question = $(tag).data('question');
    var after = $(tag).data('after');
    var on_error = $(tag).data('on-error');
    
    question = (typeof question === 'undefined') ? 'Tem certeza?' : question;

    return deleteActivate ('POST', 'DELETE', url, question, after, on_error);
    
}


function activateClick(tag) {
    
    var url = $(tag).attr('href');
    var question = $(tag).data('question');
    var after = $(tag).data('after');
    var on_error = $(tag).data('on-error');
    
    question = (typeof question === 'undefined') ? 'Tem certeza?' : question;
    
    return deleteActivate ('PUT', 'PUT', url, question, after, on_error);
    
}


function inicializa(elemento) {
    $(elemento).find('a[data-delete]').click(function(event) {
        event.preventDefault();
        return deleteClick($(this));
    });
    $(elemento).find('a[data-activate]').click(function(event) {
        event.preventDefault();
        return activateClick($(this));
    });
}

$(document).ready(function() {
    inicializa('*');
});  



