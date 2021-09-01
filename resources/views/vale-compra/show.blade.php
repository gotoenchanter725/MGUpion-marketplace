@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-8'>
        <div class='card'>
            <h4 class="card-header">Produtos</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tbody>
                    @foreach ($model->ValeCompraProdutoBarraS as $vcmpb)
                        <tr> 
                            <td>{{ $vcmpb->ProdutoBarra->barras }}</td> 
                            <td>
                                <?php $inativo = $vcmpb->ProdutoBarra->Produto->inativo; ?>
                                @if (!empty($inativo))
                                  <s><a href='{{ url('produto', $vcmpb->ProdutoBarra->codproduto) }}'>{{ $vcmpb->ProdutoBarra->descricao }}</a></s>
                                  <span class='text-danger'>
                                      inativo desde {{ formataData($vcmpb->ProdutoBarra->Produto->inativo) }}
                                  </span>
                                @else
                                  <a href='{{ url('produto', $vcmpb->ProdutoBarra->codproduto) }}'>
                                    {{ $vcmpb->ProdutoBarra->descricao }}
                                  </a>
                                @endif                               
                            </td>
                            <td>
                                {{ formataNumero($vcmpb->quantidade, 3) }}
                                {{ $vcmpb->ProdutoBarra->UnidadeMedida->sigla }}
                            </td>
                            <td>{{ formataNumero($vcmpb->preco, 2) }}</td>
                            <td>{{ formataNumero($vcmpb->total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        @if (!empty($model->desconto))
                        <tr>
                            <td colspan="3"></td>
                            <td><strong class="pull-right">Total Produtos</strong></td>
                            <td><strong>{{ formataNumero($model->totalprodutos, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td><strong class="pull-right">Desconto</strong></td>
                            <td><strong>{{ formataNumero($model->desconto, 2) }}</strong></td>
                        </tr>
                        @endif
                        <tr>
                          <td colspan="3"></td>
                          <td><strong class="pull-right">Total</strong></td>
                          <td><strong>{{ formataNumero($model->total, 2) }}</strong></td>
                        </tr>                        
                    </tfoot>
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    
    <div class='col-md-4'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tbody>  
                        <tr> 
                            <th>#</th> 
                            <td>{{ formataCodigo($model->codvalecompra) }}</td> 
                        </tr>
                        <tr> 
                            <th>Aluno</th> 
                            <td>{{ $model->aluno }}</td> 
                        </tr>
                        <tr> 
                            <th>Turma</th> 
                            <td>{{ $model->turma }}</td> 
                        </tr>
                        <tr> 
                            <th>Pessoa</th> 
                            <td>
                                  <a href='{{ url('pessoa', $model->codpessoafavorecido) }}'>
                                  {{ $model->PessoaFavorecido->fantasia }}
                                  </a>
                            </td> 
                        <tr> 
                            <th>Pagamento</th> 
                            <td>
                            @foreach ($model->ValeCompraFormaPagamentoS as $pag)
                                {{ $pag->FormaPagamento->formapagamento }}
                                @foreach ($pag->TituloS as $titulo)
                                  <br>
                                  <a href='{{ url('titulo', $titulo->codtitulo) }}'>{{ $titulo->numero }}</a>
                                  <small class="pull-right text-muted">
                                    Saldo: {{ formataNumero($titulo->saldo) }}
                                  </small>
                                @endforeach
                            @endforeach                                
                            </td> 
                        </tr>
                        <tr> 
                            <th>Favorecido</th> 
                            <td>
                                <a href='{{ url('pessoa', $model->codpessoafavorecido) }}'>
                                    {{ $model->PessoaFavorecido->fantasia }}
                                </a>
                            </td> 
                        </tr>
                        <tr> 
                            <th>Modelo</th> 
                            <td>
                                <a href='{{ url('vale-compra-modelo', $model->codvalecompramodelo) }}'>
                                  {{ $model->ValeCompraModelo->modelo }}
                                </a>                                
                            </td> 
                        </tr>
                        <tr> 
                            <th>Crédito</th> 
                            <td>
                                <a href='{{ url('titulo', $model->codtitulo) }}'>
                                  {{ $model->Titulo->numero }}
                                </a>                                
                            </td> 
                        </tr>
                        <tr> 
                            <th>Saldo</th> 
                            <td>{{ formataNumero($model->Titulo->saldo * -1) }}</td> 
                        </tr>
                        <tr> 
                            <th>Observações</th> 
                            <td>{!! nl2br($model->observacoes) !!}</td> 
                        </tr>
                    </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>


<!-- Large modal -->

<div class="modal fade" id="modalRelatorio" tabindex="-1" role="dialog" aria-labelledby="modalRelatorioLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <iframe id="frameImpressao" src="" style="border: 0px; width: 100%; height: 300px">
        </iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button class="btn btn-primary" title="Imprimir" id="btnImprimir">
            <i class="fa fa-print"></i>
        </button>
      </div>
    </div>
  </div>
</div>

@section('buttons')

    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra/$model->codvalecompra/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar o vale '{{ formataCodigo($model->codvalecompra) }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra/$model->codvalecompra/activate") }}" data-activate data-question="Tem certeza que deseja ativar o vale '{{ formataCodigo($model->codvalecompra) }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" title='Impressão' href="#" data-toggle="modal" data-target="#modalRelatorio" id="linkImpressao"><span class="fa fa-print"></span></a>
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    
    @if ($imprimir == true)
        $('#frameImpressao').attr('src', '{{ url("vale-compra/{$model->codvalecompra}/imprimir?imprimir=true") }}');
        $('#modalRelatorio').modal('show')
    @endif
    
    $('#linkImpressao').click(function (e) {
        $('#frameImpressao').attr('src', '{{ url("vale-compra/{$model->codvalecompra}/imprimir?imprimir=false") }}');
    });
    
    
    $('#btnImprimir').click(function (e) {
        $('#frameImpressao').attr('src', '{{ url("vale-compra/{$model->codvalecompra}/imprimir?imprimir=true") }}');
        $('#modalRelatorio').modal('hide')
        swal({
          title: "Documento enviado para impressora!",
          type: "error",
          closeOnConfirm: true,
          closeOnCancel: true
        });         
    });
    
});
</script>
@endsection
@stop