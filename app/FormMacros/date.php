<?php
    
use Carbon\Carbon;
use Collective\Html\FormFacade;

Form::macro('datetimeLocalMG', function($name, $value = null, $options = [])
{
    // Formata Valor para datetimeLocal
    $value = Form::getValueAttribute($name, $value);
    if ($value instanceof Carbon || $value instanceof DateTime) {
        $value = $value->format('Y-m-d\TH:i');
    }

    return $this->input('datetime-local', $name, $value, $options);
});
