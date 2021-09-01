@extends('layouts.quiosque')
@section('content')

<form class="form-inline pull-right">
  <input type="text" id="busca" autofocus placeholder="Busca" class="form-control" />
  <button type="button" class="btn btn-info" id="btnInicio">Início</button>
  <button type="button" class="btn " id="btnFechar">Fechar</button>
</form>

<div class="portfolioFilter">
  <a href="#" data-filter="*" id="btnTodos" class="current waves-effect waves-light">Todos</a>
  @foreach ($itens['prancheta']->sortBy('prancheta') as $prancheta)
  <a href="#" data-filter=".prancheta-{{ $prancheta->codprancheta }}" class="waves-effect waves-light">{{ $prancheta->prancheta }}</a>
  @endforeach
<br>
  @foreach ($itens['marca']->sortBy('marca') as $marca)
  <a href="#" data-filter=".marca-{{ $marca->codmarca }}" class="waves-effect waves-light">
    @if (!empty($marca->codimagem))
    <img height="50" src='{{ asset("public/imagens/{$marca->Imagem->observacoes}") }}' alt='{{$marca->marca}}' title="{{$marca->marca}}">
    @else
    {{ $marca->marca }}
    @endif
  </a>
  @endforeach
</div>


<div class="row port m-b-20">
  <div class="portfolioContainer">
    @foreach ($itens['produto'] as $produto)
    <div class="col-md-2 marca-{{ $produto->codmarca }} prancheta-{{ $produto->codprancheta }} ">
      <div class="thumb">
        <a href="{{ url("prancheta/quiosque/produto/{$produto->codpranchetaproduto}/{$codestoquelocal}") }}" class="image-popup" title="{{ $produto->produto }}">
          @if ($itens['imagem'][$produto->codproduto]->count() > 0)
          <div id="car{{ $produto->codproduto }}" class="carousel slide" data-ride="carousel" data-interval="1500" >
            <div class="carousel-caption d-none d-md-block" style="">
              <h4><small>R$</small> {{ formataNumero($produto->preco, 2) }}</h4>
              {{ $produto->produto }}
              <br>
              <i class="fa fa-cubes"></i>
              @if (!empty($produto->saldoquantidade))
              {{ formataNumero($produto->saldoquantidade, 0) }}
              @else
              Sem Saldo
              @endif
            </div>
            <div class="carousel-inner" role="listbox">
              @foreach ($itens['imagem'][$produto->codproduto] as $imagem)
              <div class="carousel-item {{ ($loop->first)?'active':'' }} ">
                <img class="d-block img-fluid thumb-img" src="{{$imagem->url}}" alt="{{ $produto->produto }}">
              </div>
              @endforeach
            </div>
          </div>
          @else
          <div class="gal-detail text-xs-center">
            <h4 class="m-t-10">{{ $produto->produto }}</h4>
            <h4><small>R$</small> {{ formataNumero($produto->preco, 2) }}</h4>
            <p class="text-muted">
              <i class="fa fa-cubes"></i>
              @if (!empty($produto->saldoquantidade))
              {{ formataNumero($produto->saldoquantidade, 0) }}
              @else
              Sem Saldo
              @endif
            </p>
          </div>
          @endif
        </a>
      </div>
    </div>
    @endforeach
  </div><!-- end portfoliocontainer-->
</div> <!-- End row -->

@section('inscript')
<script src="{{ asset('public/assets/plugins/isotope/js/isotope.pkgd.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function () {

    $("#btnInicio").click(function (e) {
        e.preventDefault();
        location.href = "{{ url('prancheta/quiosque', $codestoquelocal) }}";
    });

    $("#btnFechar").click(function (e) {
        e.preventDefault();
        window.parent.fechaPrancheta();
    });

});


$(window).load(function () {

    var $container = $('.portfolioContainer');
    $container.isotope({
        filter: '*',
        animationOptions: {
            duration: 750,
            easing: 'linear',
            queue: false
        }
    });

    $('.portfolioFilter a').click(function () {
        $('.portfolioFilter .current').removeClass('current');
        $(this).addClass('current');

        var selector = $(this).attr('data-filter');
        $container.isotope({
            filter: selector,
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });

        $('#busca').val('');

        return false;
    });

    $('#busca').keyup(function (e) {

        if (e.keyCode == 27) {
            window.parent.fechaPrancheta();
            return;
        }

        var busca = $('#busca').val();
        busca = busca.split(' ');
        
        $('.portfolioFilter .current').removeClass('current');
        $('#btnTodos').addClass('current');

        $container.isotope({
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            },
            filter: function () {
                var achou = true;
                busca.forEach(function(termo) {
                    if (! $(this).text().match(new RegExp(termo, 'gi'))) {
                        achou = false;
                    }
                }, $(this));
                return achou;
            }
        });
    });


});
</script>

@endsection
@stop
