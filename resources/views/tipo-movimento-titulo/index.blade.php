@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codtipomovimentotitulo" class="control-label">#</label>
                        {!! Form::number('codtipomovimentotitulo', null, ['class'=> 'form-control', 'id'=>'codtipomovimentotitulo', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipomovimentotitulo" class="control-label">Tipo Movimento Titulo</label>
                        {!! Form::text('tipomovimentotitulo', null, ['class'=> 'form-control', 'id'=>'tipomovimentotitulo']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="implantacao" class="control-label">Implantacao</label>
                        {!! Form::text('implantacao', null, ['class'=> 'form-control', 'id'=>'implantacao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="ajuste" class="control-label">Ajuste</label>
                        {!! Form::text('ajuste', null, ['class'=> 'form-control', 'id'=>'ajuste']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="armotizacao" class="control-label">Armotizacao</label>
                        {!! Form::text('armotizacao', null, ['class'=> 'form-control', 'id'=>'armotizacao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="juros" class="control-label">Juros</label>
                        {!! Form::text('juros', null, ['class'=> 'form-control', 'id'=>'juros']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="desconto" class="control-label">Desconto</label>
                        {!! Form::text('desconto', null, ['class'=> 'form-control', 'id'=>'desconto']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="pagamento" class="control-label">Pagamento</label>
                        {!! Form::text('pagamento', null, ['class'=> 'form-control', 'id'=>'pagamento']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="estorno" class="control-label">Estorno</label>
                        {!! Form::text('estorno', null, ['class'=> 'form-control', 'id'=>'estorno']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="observacao" class="control-label">Observacao</label>
                        {!! Form::text('observacao', null, ['class'=> 'form-control', 'id'=>'observacao']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="inativo" class="control-label">Ativos</label>
                        {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                    </div>
                </div>
                <div class="clearfix"></div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>

<div class='card'>
    <div class='card-block table-responsive'>
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Tipo Movimento Titulo', 'Implantacao', 'Ajuste', 'Armotizacao', 'Juros', 'Desconto', 'Pagamento', 'Estorno', 'Observacao', ]])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("tipo-movimento-titulo/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

    @include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('tipo-movimento-titulo/datatable'), 'order' => $filtro['order'], 'filtros' => ['codtipomovimentotitulo', 'tipomovimentotitulo', 'inativo', 'implantacao', 'ajuste', 'armotizacao', 'juros', 'desconto', 'pagamento', 'estorno', 'observacao', ] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@endsection
@stop
