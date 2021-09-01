@extends('layouts.default')
@section('content')
{!! Form::model($data, ['method' => 'PATCH', 'id' => 'form-principal', 'action' => ['ProdutoController@site', $data['codproduto']] ]) !!}
@include('errors.form_error')

@foreach ($data['codprodutoembalagem'] as $codprodutoembalagem)
  @include('produto.site-dimensoes', ['codprodutoembalagem'=>$codprodutoembalagem])
@endforeach

<div class="card">
  <h4 class="card-header">
    Principal
  </h4>
  <div class="card-block">
    <fieldset class="form-group">
      <div class="checkbox checkbox-primary pull-left">
        {!! Form::checkbox('site', true, null, ['class'=> 'form-control', 'id'=>'site']) !!}
        {!! Form::label('site', 'Disponível no Site') !!}
      </div>
    </fieldset> 
    <fieldset class="form-group">
      {!! Form::label('metakeywordsite', 'Meta Keywords') !!}
      {!! Form::text('metakeywordsite', null, ['class'=> 'form-control', 'id'=>'metakeywordsite', 'rows'=>'3']) !!}
    </fieldset>
    <fieldset class="form-group">
      {!! Form::label('metadescriptionsite', 'Meta Description') !!}
      {!! Form::text('metadescriptionsite', null, ['class'=> 'form-control', 'id'=>'metadescriptionsite', 'rows'=>'3']) !!}
    </fieldset>
    <fieldset class="form-group">
    </fieldset>
    <fieldset class="form-group">
      {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
    </fieldset>
  </div>
</div>


{!! Form::close() !!}
@section('inactive')

@include('layouts.includes.inactive', [$data])

@endsection
@section('creation')

@include('layouts.includes.creation', [$data])

@endsection
@section('inscript')

<!-- include summernote css/js-->
<link href="{{ asset('public/assets/plugins/summernote/summernote.css') }}" rel="stylesheet">
<script src="{{ asset('public/assets/plugins/summernote/summernote.js') }}"></script>
<script src="{{ asset('public/assets/plugins/summernote/lang/summernote-pt-BR.js') }}"></script>

<script type="text/javascript">
    
var canvas = {};
var ctx = {};
var cubos = {};
var proporcao = {};
var iResma = 0;
var iCaneta = 1;
var iProduto = 2;

function inicializaCanvas(codprodutoembalagem) {

    // Inicializa Canvas
    canvas[codprodutoembalagem] = $('#canvas-' + codprodutoembalagem).get(0);
    canvas[codprodutoembalagem].width = $('#div-canvas-' + codprodutoembalagem).width();
    canvas[codprodutoembalagem].height = $('#div-canvas-' + codprodutoembalagem).height();
    
    ctx[codprodutoembalagem] = canvas[codprodutoembalagem].getContext('2d');
    
    // Cubos que serao desenhados
    cubos[codprodutoembalagem] = [
        {
            descricao: 'Resma A4',
            cor: '#99BB99',
            largura: 21,
            altura: 29.7,
            profundidade: 5,
            larguraProporcional: 0,
            alturaProporcional: 0,
            profundidadeProporcional: 0,
            posicaoX: 0,
            posicaoy: 0,
        },
        {
            descricao: 'Caneta',
            cor: '#000099',
            largura: 1.3,
            altura: 15,
            profundidade: 1,
            larguraProporcional: 0,
            alturaProporcional: 0,
            profundidadeProporcional: 0,
            posicaoX: 0,
            posicaoy: 0,
        },
        {
            descricao: 'Produto',
            cor: '#ff8d4b',
            largura: 0,
            altura: 0,
            profundidade: 0,
            larguraProporcional: 0,
            alturaProporcional: 0,
            profundidadeProporcional: 0,
            posicaoX: 0,
            posicaoy: 0,
        },
    ];

    // Escala de proporção utilizada no desenho
    proporcao[codprodutoembalagem] = 0;

    calculaProporcao(codprodutoembalagem);

}

// calcula a proporcao mais apropriada com base nos valores informados
function calculaProporcao(codprodutoembalagem) {

    carregaDimensoesProduto(codprodutoembalagem);

    // busca maior altura
    // soma larguras e profundidades
    var altura = 0;
    var largura = 0;
    for (i = 0; i < cubos[codprodutoembalagem].length; i++) {
        largura += cubos[codprodutoembalagem][i].largura;
        largura += cubos[codprodutoembalagem][i].profundidade;
        if (cubos[codprodutoembalagem][i].altura > altura) {
            altura = cubos[codprodutoembalagem][i].altura;
        }
    }

    // calcula proporcao necessarias para apareceter toda largura e toda altura
    var proporcaoLargura = canvas[codprodutoembalagem].width / largura;
    var proporcaoAltura = canvas[codprodutoembalagem].height / (altura + (largura / 2));

    // utiliza a menor proporcao
    if (proporcaoLargura < proporcaoAltura) {
        proporcao[codprodutoembalagem] = proporcaoLargura;
    } else {
        proporcao[codprodutoembalagem] = proporcaoAltura;
    }

    // desenha os cubos
    desenha(codprodutoembalagem);
}

// carrega as dimensoes do produto
function carregaDimensoesProduto(codprodutoembalagem) {
    cubos[codprodutoembalagem][iProduto].largura = Number($("#largura-" + codprodutoembalagem).val());
    cubos[codprodutoembalagem][iProduto].profundidade = Number($("#profundidade-" + codprodutoembalagem).val());
    cubos[codprodutoembalagem][iProduto].altura = Number($("#altura-" + codprodutoembalagem).val());
}

// aplica a proporcao nas dimensoes
function aplicaProporcao(codprodutoembalagem) {
    var aproximacao = Number($("#aproximacao-" + codprodutoembalagem).val());
    for (i = 0; i < cubos[codprodutoembalagem].length; i++) {
        cubos[codprodutoembalagem][i].larguraProporcional = cubos[codprodutoembalagem][i].largura * proporcao[codprodutoembalagem] * aproximacao;
        cubos[codprodutoembalagem][i].profundidadeProporcional = cubos[codprodutoembalagem][i].profundidade * proporcao[codprodutoembalagem] * aproximacao;
        cubos[codprodutoembalagem][i].alturaProporcional = cubos[codprodutoembalagem][i].altura * proporcao[codprodutoembalagem] * aproximacao;
    }
}

// Desenha todos os cubos
function desenha(codprodutoembalagem) {
    
    // Limpa todos desenhos do canvas
    ctx[codprodutoembalagem].clearRect(0, 0, canvas[codprodutoembalagem].width, canvas[codprodutoembalagem].height);

    // Carrega Dimensoes Produto
    carregaDimensoesProduto(codprodutoembalagem);
    
    if (cubos[codprodutoembalagem][iProduto].largura == 0 || 
        cubos[codprodutoembalagem][iProduto].profundidade == 0 ||
        cubos[codprodutoembalagem][iProduto].altura == 0) {
        $("#canvas-" + codprodutoembalagem).fadeOut();
        return;
    }

    // Dimensiona Conforme Proporcao
    aplicaProporcao(codprodutoembalagem);

    var larguraTotal = 0;
    var profundidadeTotal = 0;
    var maiorAltura = 0;
    for (i = 0; i < cubos[codprodutoembalagem].length; i++) {
        if (cubos[codprodutoembalagem][i].alturaProporcional > maiorAltura) {
            maiorAltura =
                    cubos[codprodutoembalagem][i].alturaProporcional
                    + (cubos[codprodutoembalagem][i].larguraProporcional * 0.5)
                    + (cubos[codprodutoembalagem][i].profundidadeProporcional * 0.5)
                    ;
        }
        larguraTotal += cubos[codprodutoembalagem][i].larguraProporcional;
        profundidadeTotal += cubos[codprodutoembalagem][i].profundidadeProporcional;
    }
    var margemX = (canvas[codprodutoembalagem].width - larguraTotal - profundidadeTotal) / 2;
    var margemY = (canvas[codprodutoembalagem].height - maiorAltura) / 2;
    var posicaoY = margemY + maiorAltura;

    // Posiciona os cubos no eixo X
    cubos[codprodutoembalagem][iCaneta].posicaoX =
            margemX
            + cubos[codprodutoembalagem][iCaneta].larguraProporcional
            ;
    cubos[codprodutoembalagem][iProduto].posicaoX =
            cubos[codprodutoembalagem][iCaneta].posicaoX
            + cubos[codprodutoembalagem][iCaneta].profundidadeProporcional
            + cubos[codprodutoembalagem][iProduto].larguraProporcional
            ;
    cubos[codprodutoembalagem][iResma].posicaoX =
            cubos[codprodutoembalagem][iProduto].posicaoX
            + cubos[codprodutoembalagem][iProduto].profundidadeProporcional
            + cubos[codprodutoembalagem][iResma].larguraProporcional
            ;

    // Posiciona os cubos no eixo Y
    cubos[codprodutoembalagem][iProduto].posicaoY = posicaoY;
    cubos[codprodutoembalagem][iResma].posicaoY = posicaoY;
    cubos[codprodutoembalagem][iCaneta].posicaoY = posicaoY;

    // Desenha os cubos
    yLabel = 0;
    for (i = 0; i < cubos[codprodutoembalagem].length; i++) {
        desenhaCubo(
                codprodutoembalagem,
                cubos[codprodutoembalagem][i].posicaoX,
                cubos[codprodutoembalagem][i].posicaoY,
                cubos[codprodutoembalagem][i].larguraProporcional,
                cubos[codprodutoembalagem][i].profundidadeProporcional,
                cubos[codprodutoembalagem][i].alturaProporcional,
                cubos[codprodutoembalagem][i].cor,
                cubos[codprodutoembalagem][i].descricao
                );
    }
    $("#canvas-" + codprodutoembalagem).fadeIn();
}

// Colour adjustment function
// Nicked from http://stackoverflow.com/questions/5560248
function shadeColor(cor, percent) {
    cor = cor.substr(1);
    var num = parseInt(cor, 16),
            amt = Math.round(2.55 * percent),
            R = (num >> 16) + amt,
            G = (num >> 8 & 0x00FF) + amt,
            B = (num & 0x0000FF) + amt;
    return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 + (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 + (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
}

// desenha um cubo
yLabel = 0;
function desenhaCubo(codprodutoembalagem, x, y, largura, profundidade, altura, cor, texto) {

    // Label
    yLabel += 18;
    ctx[codprodutoembalagem].fillStyle = shadeColor(cor, -10);
    ctx[codprodutoembalagem].font = "15px Verdana";
    ctx[codprodutoembalagem].fillText(texto, 10, yLabel);

    // Largura
    ctx[codprodutoembalagem].beginPath();
    ctx[codprodutoembalagem].moveTo(x, y);
    ctx[codprodutoembalagem].lineTo(x - largura, y - largura * 0.5);
    ctx[codprodutoembalagem].lineTo(x - largura, y - altura - largura * 0.5);
    ctx[codprodutoembalagem].lineTo(x, y - altura * 1);
    ctx[codprodutoembalagem].closePath();
    ctx[codprodutoembalagem].fillStyle = shadeColor(cor, -10);
    ctx[codprodutoembalagem].strokeStyle = cor;
    ctx[codprodutoembalagem].stroke();
    ctx[codprodutoembalagem].fill();

    // Profundidade
    ctx[codprodutoembalagem].beginPath();
    ctx[codprodutoembalagem].moveTo(x, y);
    ctx[codprodutoembalagem].lineTo(x + profundidade, y - profundidade * 0.5);
    ctx[codprodutoembalagem].lineTo(x + profundidade, y - altura - profundidade * 0.5);
    ctx[codprodutoembalagem].lineTo(x, y - altura * 1);
    ctx[codprodutoembalagem].closePath();
    ctx[codprodutoembalagem].fillStyle = shadeColor(cor, 10);
    ctx[codprodutoembalagem].strokeStyle = shadeColor(cor, 50);
    ctx[codprodutoembalagem].stroke();
    ctx[codprodutoembalagem].fill();

    // Altura
    ctx[codprodutoembalagem].beginPath();
    ctx[codprodutoembalagem].moveTo(x, y - altura);
    ctx[codprodutoembalagem].lineTo(x - largura, y - altura - largura * 0.5);
    ctx[codprodutoembalagem].lineTo(x - largura + profundidade, y - altura - (largura * 0.5 + profundidade * 0.5));
    ctx[codprodutoembalagem].lineTo(x + profundidade, y - altura - profundidade * 0.5);
    ctx[codprodutoembalagem].closePath();
    ctx[codprodutoembalagem].fillStyle = shadeColor(cor, 20);
    ctx[codprodutoembalagem].strokeStyle = shadeColor(cor, 60);
    ctx[codprodutoembalagem].stroke();
    ctx[codprodutoembalagem].fill();
}

function calculaPeso(codprodutoembalagem) {
    $('#imagem-peso-' + codprodutoembalagem).fadeOut(function () { mostraPeso(codprodutoembalagem) });
}

function mostraPeso(codprodutoembalagem) {
    
    var peso = Number($('#peso-' + codprodutoembalagem).val());
  
    var imagem = ''
    var peso_imagem = 0;
    var descricao = '';
    var descricaoS = '';
    
    if (peso >= 20) {
        imagem = '{{ asset('public/img/a4caixa.jpg') }}';
        peso_imagem = 23.5;
        descricao = 'Caixa de Papel A4';
        descricaoS = 'Caixas de Papel A4';
    } else if (peso >= 2) {
        imagem = '{{ asset('public/img/a4resma.jpg') }}';
        peso_imagem = 2.35;
        descricao = 'Resma de Papel A4';
        descricaoS = 'Resmas de Papel A4';
    } else if (peso >= 0.9) {
        imagem = '{{ asset('public/img/colakg.jpg') }}';
        peso_imagem = 1;
        descricao = 'Cola Branca em KG';
        descricaoS = 'Colas Brancas em KG';
    } else if (peso >= 0.25) {
        imagem = '{{ asset('public/img/canetacaixa.jpg') }}';
        peso_imagem = 0.3;
        descricao = 'Caixa com 50 Canetas';
        descricaoS = 'Caixas com 50 Canetas';
    } else if (peso != 0) {
        imagem = '{{ asset('public/img/caneta.jpg') }}';
        peso_imagem = 0.006;
        descricao = 'Caneta';
        descricaoS = 'Canetas';
    }

    if (peso > 0) {
        
        var quantidade = parseInt((peso/peso_imagem)*10)/10;

        if (quantidade >= 2) {
          descricao = descricaoS;
        }

        var html = quantidade + ' ' + descricao;

        $('#imagem-peso-' + codprodutoembalagem).attr('src', imagem);
        $('#descricao-peso-' + codprodutoembalagem).html(html);
        
        $('#imagem-peso-' + codprodutoembalagem).fadeIn();
        $('#descricao-peso-' + codprodutoembalagem).fadeIn();

    } else {
        $('#descricao-peso-' + codprodutoembalagem).html('Peso');        
    }
    
}

$(document).ready(function () {
    
    $('.descricaosite').summernote({ 
        lang: 'pt-BR',
        airMode: false,  // Add fade effect on dialogs
        popover: false,  // Add fade effect on dialogs
        dialogsFade: true,  // Add fade effect on dialogs
    });

    inicializaCanvas(0);
    calculaPeso(0);
    @foreach ($data['codprodutoembalagem'] as $codprodutoembalagem)
      inicializaCanvas({{$codprodutoembalagem}});
      calculaPeso({{$codprodutoembalagem}});
    @endforeach

    // Redimensiona proporcoes
    $('.altura').keyup(function () {
        var codprodutoembalagem = $(this).data('codprodutoembalagem');
        calculaProporcao(codprodutoembalagem)
    });
    $('.largura').keyup(function () {
        var codprodutoembalagem = $(this).data('codprodutoembalagem');
        calculaProporcao(codprodutoembalagem)
    });
    $('.profundidade').keyup(function () {
        var codprodutoembalagem = $(this).data('codprodutoembalagem');
        calculaProporcao(codprodutoembalagem)
    });

    // somente desenha novamente
    $('.aproximacao').change(function () {
        var codprodutoembalagem = $(this).data('codprodutoembalagem');
        desenha(codprodutoembalagem)
    });

    // mostra imagem peso
    $('.peso').change(function () {
        var codprodutoembalagem = $(this).data('codprodutoembalagem');
        calculaPeso(codprodutoembalagem)
    });

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
    
    
    //$("#produto").Setcase();

});
</script>
@endsection
@stop