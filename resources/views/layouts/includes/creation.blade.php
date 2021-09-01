@if (!empty($model->criacao) || !empty($model->codusuariocriacao))
  Criado 
  @if (!empty($model->criacao) )
      em {{ formataData($model->criacao, 'L') }}
  @endif
  @if (!empty($model->codusuariocriacao) )
      por <a href="{{ url('usuario', $model->codusuariocriacao) }}">{{$model->UsuarioCriacao->usuario}}</a> 
  @endif
@endif
@if ((!empty($model->alteracao) && ($model->alteracao != $model->criacao)) || !empty($model->codusuarioalteracao))
  Alterado 
  @if ((!empty($model->alteracao) && ($model->alteracao != $model->criacao)))
      em {{ formataData($model->alteracao, 'L') }}
  @endif
  @if (!empty($model->codusuarioalteracao) )
      por <a href="{{ url('usuario', $model->codusuarioalteracao) }}">{{$model->UsuarioAlteracao->usuario}}</a> 
  @endif
@endif 