@extends('layouts.default')
@section('content')
<div class='row'>
    <div class='col-md-6'>
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="table table-bordered table-striped table-hover table-sm col-md-12">
                    <tbody>  
                        <tr> 
                            <th>#</th> 
                            <td>{{ formataCodigo($model->codusuario) }}</td> 
                        </tr>
                        <tr> 
                            <th>Usuário</th> 
                            <td>{{ $model->usuario }}</td> 
                        </tr>
                        <tr> 
                            <th>Filial</th> 
                            <td>{{ $model->Filial->filial or '' }}</td> 
                        </tr>
                        <tr> 
                            <th>Pessoa</th> 
                            <td>{{ $model->Pessoa->pessoa or ''}}</td> 
                        </tr>
                        <tr> 
                            <th>Impressora Matricial</th> 
                            <td>{{ $model->impressoramatricial }}</td> 
                        </tr> 
                        <tr> 
                            <th>Impressora Térmica</th> 
                            <td>{{ $model->impressoratermica }}</td> 
                        </tr>
                        <tr> 
                            <th>Impressora tela negócio</th> 
                            <td>{{ $model->impressoratelanegocio }}</td> 
                        </tr>
                        <tr> 
                            <th>Último acesso</th> 
                            <td>{{ formataData($model->ultimoacesso, 'L') }}</td> 
                        </tr>           
                    </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>

    @section('buttons')
        <div class="btn-group pull-right" role="group" aria-label="Controles">
            <a class="btn btn-secondary btn-sm" href="{{ url("usuario/$model->codusuario/edit") }}"><i class="fa fa-pencil"></i></a>
            @if (empty($model->inativo))
                <a class="btn btn-secondary btn-sm" href="{{ url("usuario/$model->codusuario/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->usuario }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
            @else
                <a class="btn btn-secondary btn-sm" href="{{ url("usuario/$model->codusuario/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->usuario }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
            @endif
             <a class="btn btn-secondary btn-sm" href="{{ url("usuario/$model->codusuario") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->usuario }}'?" data-after="location.replace('{{ url('usuario') }}');"><i class="fa fa-trash"></i></a> 
        </div> 
    @endsection
    
    <div class="col-sm-6 col-xs-12">
        <div class="card">
            <h4 class="card-header">
                Grupos
                <div class="btn-group pull-right" role="group" aria-label="Controles">
                    <a class="btn btn-secondary btn-sm" href="{{ url("usuario/$model->codusuario/grupos") }}"><i class="fa fa-pencil"></i></a>
                </div>
            </h4>
            <div class="card-block">
                <div class="card-text">
                    <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                      <tbody>  
                        <tr> 
                          <th>Grupo</th> 
                          <th>Filial</th> 
                          <th>Desde</th> 
                        </tr>
                        @foreach ($model->GrupoUsuarioUsuarioS()->select(['tblgrupousuario.codgrupousuario', 'tblgrupousuario.grupousuario', 'tblfilial.codfilial', 'tblfilial.filial', 'tblgrupousuariousuario.criacao'])->join('tblgrupousuario', 'tblgrupousuario.codgrupousuario', 'tblgrupousuariousuario.codgrupousuario')->join('tblfilial', 'tblfilial.codfilial', 'tblgrupousuariousuario.codfilial')->orderBy('tblgrupousuario.grupousuario')->orderBy('tblfilial.filial')->whereNull('tblgrupousuario.inativo')->get() as $guu)
                        <tr> 
                          <td>
                            <a href='{{ url('grupo-usuario', $guu->codgrupousuario) }}'>
                              {{ $guu->grupousuario }}
                            </a>
                          </td> 
                          <td>
                            <a href='{{ url('filial', $guu->codfilial) }}'>
                              {{ $guu->filial }}
                            </a>
                          </td> 
                          <td>{{ formataData($guu->criacao, 'C') }}</td> 
                        </tr>
                        @endforeach
                      </tbody> 
                    </table>
                </div>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop


