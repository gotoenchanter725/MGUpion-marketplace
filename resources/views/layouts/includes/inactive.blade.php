@if (!empty($model->inativo))
    Inativo desde {{ formataData($model->inativo, 'L') }}
@endif
