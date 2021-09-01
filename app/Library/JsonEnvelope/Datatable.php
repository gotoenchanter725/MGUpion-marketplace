<?php

namespace MGLara\Library\JsonEnvelope;

use Illuminate\Support\Facades\Response;

/**
 * Description of EnvelopeJson
 *
 * @author escmig98
 * @property int $draw Número da requisicao
 * @property int $recordsTotal Total de registros da Tabela
 * @property int $recordsFiltered Total de registros da após aplicação do filtro
 * @property array $data Resultado
 */
class Datatable {

    public function __construct($draw = 1, $recordsTotal = 0, $recordsFiltered = 0, $data = []) {
        $this->draw = $draw;
        $this->recordsTotal = $recordsTotal;
        $this->recordsFiltered = $recordsFiltered;
        $this->data = $data;
    }
    
    public function response() {
        return Response::json($this);
    }
    
}
