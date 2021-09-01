<?php
    
if ($model->ultimaconferencia == null) {
    $class = 'fa-times-circle text-muted';
} else {

    $dias = $model->ultimaconferencia->diffInDays();

    if ($dias > 60) {
        $class = 'fa-times-circle text-danger';
    } else if ($dias > 30) {
        $class = 'fa-question-circle text-warning';
    } else {
        $class = 'fa-check-circle text-success';
    }
}

?>

<a href="{{ url('estoque-saldo', $model->codestoquesaldo) }}">
  <div>
    <b>
      <i class="fa {{ $class }}"></i> &nbsp;
      {{ formataNumero($model->saldoquantidade, 3) }}
    </b>
    <br>
    <small class='text-muted'>
        {{ formataNumero($model->customedio, 6) }}
    </small>      
  </div>
</a>
