@@extends('layouts.default')
@@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            @{!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="{{ $instancia_model->getKeyName() }}" class="control-label">#</label>
                        @{!! Form::number('{{ $instancia_model->getKeyName() }}', null, ['class'=> 'form-control', 'id'=>'{{ $instancia_model->getKeyName() }}', 'step'=>1, 'min'=>1]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="{{ $coluna_titulo }}" class="control-label">{{ $titulo }}</label>
                        @{!! Form::text('{{ $coluna_titulo }}', null, ['class'=> 'form-control', 'id'=>'{{ $coluna_titulo }}']) !!}
                    </div>
                </div>
@foreach ($cols_listagem as $col)
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="{{ $col->column_name }}" class="control-label">{{ ucfirst($col->column_name) }}</label>
                        @{!! Form::text('{{ $col->column_name }}', null, ['class'=> 'form-control', 'id'=>'{{ $col->column_name }}']) !!}
                    </div>
                </div>
@endforeach
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="inativo" class="control-label">Ativos</label>
                        @{!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                    </div>
                </div>
                <div class="clearfix"></div>
            @{!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>

<div class='card'>
    <div class='card-block table-responsive'>
        @@include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', '{{ $titulo }}', <?php foreach ($cols_listagem as $col) echo "'" . ucfirst($col->column_name) . "', " ?>]])
        <div class='clearfix'></div>
    </div>
</div>

@@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ '{{' }} url("{{ $url }}/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@@endsection
@@section('inscript')

    @@include('layouts.includes.datatable.assets')

    @@include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('{{ $url }}/datatable'), 'order' => $filtro['order'], 'filtros' => ['{{ $instancia_model->getKeyName() }}', '{{ $coluna_titulo }}', 'inativo', <?php foreach ($cols_listagem as $col) echo "'$col->column_name', " ?>] ])

    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>

@@endsection
@@stop
