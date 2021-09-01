@extends('layouts.default')
@section('content')
<div class='row'>
    {!! Form::open(['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-transferir-variacao', 'action' => ['ProdutoController@transferirVariacaoSalvar', $model->codproduto]]) !!}
    @include('errors.form_error')    
    <div class="col-md-6">
        <div class="card">
            <h4 class="card-header">Transferir Variação</h4>
            <div class="card-block">
                <div class="form-group">
                    <label class="col-md-3">Variações</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            @foreach($model->ProdutoVariacaoS()->orderByRaw('variacao ASC NULLS FIRST')->get() as $pv)
                                <div class="checkbox checkbox-primary">
                                    {!! Form::checkbox("codprodutovariacao[]", $pv->codprodutovariacao, false, ['id'=>'codprodutovariacao_' . $loop->index]); !!}
                                    <label for="codprodutovariacao_{{ $loop->index }}"> {{ empty($pv->variacao)?'{ Sem Variação }':$pv->variacao }} </label>
                                </div>
                            @endforeach 
                        </div>
                    </div>
                </div>                
                <div class="clearfix"></div>    
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <h4 class="card-header">Produto Destino</h4>
            <div class="card-block">
                <div class="form-group">
                    {!! Form::label('codproduto', 'Produto') !!}
                    {!! Form::select2Produto('codproduto', null, ['class'=> 'form-control', 'id' => 'codproduto', 'style'=>'width:100%']) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Transferir', array('class' => 'btn btn-primary')) !!}
                </div> 
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#form-produto-transferir-variacao').on("submit", function(e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja transferir as variações?",
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
});
</script>
@endsection

@stop
