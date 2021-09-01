@extends('layouts.default')
@section('content')

<div class="collapse in" id="collapsePesquisa">
    <div class="card">
        <h4 class="card-header">Pesquisa</h4>
        <div class="card-block">
            <div class="card-text">
            {!! Form::model($filtro, ['url' => 'estoque-saldo/relatorio-comparativo-vendas', 'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'estoque-saldo-search', 'role' => 'search', 'autocomplete' => 'off' ]) !!}
                <div class="row">
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codestoquelocaldeposito', 'Depósito') !!}
                            {!! Form::select2EstoqueLocal('codestoquelocaldeposito', null, ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('saldo_deposito', 'Saldo') !!}
                            {!! Form::select2('saldo_deposito', $arr_saldo_deposito, null, ['class' => 'form-control', 'placeholder' => 'Saldo Depósito...']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codmarca', 'Marca') !!}
                            {!! Form::select2Marca('codmarca', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('codestoquelocalfilial', 'Filial') !!}
                            {!! Form::select2EstoqueLocal('codestoquelocalfilial', null, ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-6'>
                        <div class="form-group">
                            {!! Form::label('saldo_filial', 'Saldo') !!}
                            {!! Form::select2('saldo_filial', $arr_saldo_filial, null, ['class' => 'form-control', 'placeholder' => 'Saldo Filial...']) !!}
                        </div>                    
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('datainicial', 'De') !!}
                            {!! Form::datetimeLocal('datainicial', $filtro['datainicial'], ['class'=> 'form-control text-center', 'placeholder' => 'De', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('datafinal', 'Até') !!}
                            {!! Form::datetimeLocal('datafinal', $filtro['datafinal'], ['class'=> 'form-control text-center', 'placeholder' => 'Até', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('dias_previsao', 'Previsão') !!}
                            <div class="input-group">
                                {!! Form::number('dias_previsao', null, ['class'=> 'form-control text-right', 'placeholder' => 'Dias Previsão', 'step' => 1, 'required' => true]) !!}
                                <span class="input-group-addon">Dias</span>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('minimo', 'Mínimo') !!}
                            {!! Form::select2('minimo', $arr_minimo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Mínimo...']) !!}
                        </div>                        
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            {!! Form::label('maximo', 'Máximo') !!}
                            {!! Form::select2('maximo', $arr_maximo, null, ['class' => 'form-control', 'placeholder' => 'Estoque Máximo...']) !!}
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div><button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Buscar</button></div>
                        </div>                    
                    </div>

                </div>
            </div>
            </div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')
<script type="text/javascript">
    $(document).ready(function () {
        // ...
    });
</script>
@endsection
@stop
