@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h3 class="card-header">Pesquisa</h3>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="codusuario" class="control-label">#</label>
                        {!! Form::number('codusuario', null, ['class'=> 'form-control', 'id'=>'codusuario', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="usuario" class="control-label">Usuário</label>
                        {!! Form::text('usuario', null, ['class'=> 'form-control', 'id'=>'usuario']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="inativo" class="control-label">Ativos</label>
                        {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="codfilial" class="control-label">Filial</label>
                        {!! Form::select2Filial('codfilial', null, ['class'=> 'form-control', 'id'=>'codfilial']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="codpessoa" class="control-label">Pessoa</label>
                        {!! Form::select2Pessoa('codpessoa', null, ['class'=> 'form-control', 'id'=>'codpessoa']) !!}
                    </div>
                </div>
                <div class="clearfix"></div>
            {!! Form::close() !!}
        </div>
    </div>
  </div>
</div>

<div class='card-box table-responsive'>
    
    
    @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Usuario', 'Pessoa', 'Filial']])
    
</div>
@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("usuario/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

@include('layouts.includes.datatable.assets')

@include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('usuario/datatable'), 'order' => $filtro['order'], 'filtros' => ['codusuario', 'usuario', 'codfilial', 'codpessoa', 'inativo'] ])

<script type="text/javascript">
    $(document).ready(function () {
        
    });

</script>
@endsection
@stop