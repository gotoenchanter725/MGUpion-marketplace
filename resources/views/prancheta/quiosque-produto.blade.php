@extends('layouts.quiosque')
@section('content')


<form class="form-inline pull-right" style="margin-bottom: 15px">
  <button type="button" class="btn btn-info" id="btnInicio">Início</button>
  <button type="button" class="btn " id="btnFechar">Fechar</button>
</form>

<div class="row">

  <div class="col-md-5">
    <div class="card">
      <div class="card-block">
        <h4 class="modal-title" id="modal{{ $produto->codproduto }}label">
          <small class="text-muted">{{ formataCodigo($produto->codproduto, 6) }}</small>  {{ $produto->produto }}
        </h4>
        <br>

        <div id="car{{ $produto->codproduto }}lg" data-ride="carousel" class="carousel slide" data-interval="1500">
          <div role="listbox" class="carousel-inner">
            @foreach ($produto->imagens as $imagem)
            <div class="carousel-item {{ ($loop->first)?'active':'' }} ">
              <img class="d-block img-fluid" src="{{$imagem->url}}">
            </div>
            @endforeach
          </div>
          <a href="#car{{ $produto->codproduto }}lg" role="button" data-slide="prev" class="left carousel-control"> <span aria-hidden="true" class="fa fa-angle-left"></span> <span class="sr-only">Previous</span> </a>
          <a href="#car{{ $produto->codproduto }}lg" role="button" data-slide="next" class="right carousel-control"> <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Next</span> </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-2 ">
    <div class="card">
      <div class="card-block">
        <ul class="nav nav-pills nav-stacked" role="tablist">
          @foreach ($produto->embalagens as $embalagem)
          <li class="nav-item">
            <a class="nav-link {{ ($loop->first)?'active':'' }}" data-toggle="tab" href="#variacoes{{ $produto->codproduto }}_{{ $embalagem->codprodutoembalagem }}" role="tab">
              {{ $embalagem->sigla }} C/{{ formataNumero($embalagem->quantidade, 0) }}
              <b class="pull-right">
                {{ formataNumero($embalagem->preco, 2) }}
              </b>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-block">
        @if (!empty($produto->Marca->codimagem))
        <img class='img-fluid' src='{{ asset("public/imagens/{$produto->Marca->Imagem->observacoes}") }}' alt='{{$produto->Marca->marca}}' title="{{$produto->Marca->marca}}">
        @else
        <hr>
        <h3 class='text-center'>{{ $produto->Marca->marca }}</h3>
        <hr>
        @endif
      </div>
    </div>
  </div>

  <div class='col-md-5 listagem-variacoes'>
    <div class="card">
      <div class="card-block">

        <div class="tab-content">
          @foreach ($produto->embalagens as $embalagem)
          <div class="tab-pane {{ ($loop->first)?'active':'' }}" id="variacoes{{ $produto->codproduto }}_{{ $embalagem->codprodutoembalagem }}" role="tabpanel">
            <table class="table table-hover table-sm ">
              @foreach ($embalagem->variacao as $variacao)
              <tr class="{{ ($variacao->saldoquantidade<=0)?'table-danger':'' }}">
                <td class="text-right col-md-4">
                  @foreach ($variacao->barras as $barras)
                  <a href="#" class="linkBarras" data-barras="{{ $barras->barras }}" data-dismiss="modal" >
                    {{ $barras->barras }}
                  </a><br>
                  @endforeach
                </td>
                <th class="col-md-6">
                  @if (empty($variacao->variacao))
                  {Sem Variacao}
                  @else
                  {{ $variacao->variacao }}
                  @endif
                </th>
                <td class="text-right col-md-2">
                  {{ formataNumero($variacao->saldoquantidade, 0) }}
                  <i class="fa fa-cubes"></i>
                </td>
              </tr>
              @endforeach
            </table>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

</div>

@section('inscript')
<script type="text/javascript">

$(document).ready(function () {

    $("a.linkBarras").click(function (e) {
        e.preventDefault();
        var barras = $(this).data('barras');
        window.parent.adicionaProdutoPrancheta(barras);
    });


    $("#btnInicio").click(function (e) {
        e.preventDefault();
        location.href = "{{ url('prancheta/quiosque', $codestoquelocal) }}";
    });

    $("#btnFechar").click(function (e) {
        e.preventDefault();
        window.parent.fechaPrancheta();
    });

});

</script>
@endsection
@stop
