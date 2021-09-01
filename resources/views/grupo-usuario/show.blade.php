@extends('layouts.default')
@section('content')

<div class='row'>
    <div class="col-sm-4 col-xs-12">
        <div class="card">
            <h4 class="card-header">Detalhes</h4>
            <div class="card-block">
                <div class="card-text">
                    <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                      <tbody>  
                        <tr> 
                          <th>#</th> 
                          <td>{{ $model->codgrupousuario }}</td> 
                        </tr>
                        <tr> 
                          <th>Descrição</th> 
                          <td>{{ $model->grupousuario }}</td> 
                        </tr>
                      </tbody> 
                    </table>
                </div>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-xs-12">
        <div class="card">
            <h4 class="card-header">Usuários</h4>
            <div class="card-block">
                <div class="card-text">
                    <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                      <tbody>  
                        <tr> 
                          <th>Usuário</th> 
                          <th>Filial</th> 
                          <th>Desde</th> 
                        </tr>
                        @foreach ($model->GrupoUsuarioUsuarioS()->select(['tblusuario.codusuario', 'tblusuario.usuario', 'tblfilial.codfilial', 'tblfilial.filial', 'tblgrupousuariousuario.criacao'])->join('tblusuario', 'tblusuario.codusuario', 'tblgrupousuariousuario.codusuario')->join('tblfilial', 'tblfilial.codfilial', 'tblgrupousuariousuario.codfilial')->orderBy('tblusuario.usuario')->orderBy('tblfilial.filial')->whereNull('tblusuario.inativo')->get() as $guu)
                        <tr> 
                          <td>
                            <a href='{{ url('usuario', $guu->codusuario) }}'>
                              {{ $guu->usuario }}
                            </a>
                          </td> 
                          <td>
                            <a href='{{ url('usuario', $guu->codfilial) }}'>
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
    <div class="col-sm-4 col-xs-12">
        <div class="card">
            <h4 class="card-header">Permissões</h4>
            <div class="card-block">
                <div class="card-text">
                    <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                      <tbody>  
                        <tr> 
                          <th>Permissão</th> 
                          <th>Desde</th> 
                        </tr>
                        @foreach ($model->GrupoUsuarioPermissaoS()->select(['tblpermissao.permissao', 'tblgrupousuariopermissao.criacao'])->join('tblpermissao', 'tblpermissao.codpermissao', 'tblgrupousuariopermissao.codpermissao')->orderBy('tblpermissao.permissao')->get() as $guu)
                        <tr> 
                          <td>
                              {{ $guu->permissao }}
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
    @section('buttons')

        <a class="btn btn-secondary btn-sm" href="{{ url("grupo-usuario/$model->codgrupousuario/edit") }}"><i class="fa fa-pencil"></i></a>
        @if (empty($model->inativo))
            <a class="btn btn-secondary btn-sm" href="{{ url("grupo-usuario/$model->codgrupousuario/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->grupousuario }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
        @else
            <a class="btn btn-secondary btn-sm" href="{{ url("grupo-usuario/$model->codgrupousuario/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->grupousuario }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
        @endif
        <a class="btn btn-secondary btn-sm" href="{{ url("grupo-usuario/$model->codgrupousuario") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->grupousuario }}'?" data-after="location.replace('{{ url('grupo-usuario') }}');"><i class="fa fa-trash"></i></a>  

    @endsection    
</div>
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')
<script type="text/javascript">
</script>
@endsection
@stop