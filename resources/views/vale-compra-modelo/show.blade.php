@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-8'>
        <div class='card'>
            <h4 class="card-header">Produtos</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tbody>
                    @foreach ($model->ValeCompraModeloProdutoBarraS as $vcmpb)
                    
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
                            <td><strong>Total Produtos</strong></td>
                            <td><strong>{{ formataNumero($model->totalprodutos, 2) }}</strong></td>
                            <td><strong>Desconto</strong></td>
                            <td colspan="2"><strong>{{ formataNumero($model->desconto, 2) }}</strong></td>
                        </tr>
                        @endif
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><strong>Total</strong></td>
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
                            <td>{{ formataCodigo($model->codvalecompramodelo) }}</td> 
                        </tr>
                        <tr> 
                            <th>Modelo</th> 
                            <td>{{ $model->modelo }}</td> 
                        </tr>
                        <tr> 
                            <th>Ano/Turma</th> 
                            <td>{{ $model->ano }} / {{ $model->turma }}</td> 
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
                            <th>Observacoes</th> 
                            <td>{!! nl2br($model->observacoes) !!}</td> 
                        </tr>
                    </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->modelo }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->modelo }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->modelo }}'?" data-after="location.replace('{{ url('vale-compra-modelo') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')

@endsection
@stop