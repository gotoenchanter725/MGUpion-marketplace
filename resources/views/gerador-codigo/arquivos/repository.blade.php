<?php echo "<?php\n"; ?>

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\{{ $model }};

/**
 * Description of {{ $model }}Repository
 * 
 * @property Validator $validator
 * @property {{ $model }} $model
 */
class {{ $model }}Repository extends MGRepository {
    
    public function boot() {
        $this->model = new {{ $model }}();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->{{ $instancia_model->getKeyName() }};
        }
        
        $this->validator = Validator::make($data, [
@foreach ($validacoes as $campo => $regras)
            '{{ $campo }}' => [
@foreach ($regras as $regra => $validacao)
                '{{ $validacao['rule'] }}',
@endforeach
            ],
@endforeach
        ], [
@foreach ($validacoes as $campo => $regras)
@foreach ($regras as $regra => $validacao)
@if (isset($validacao['mensagem']))
            '{{ $campo }}.{{ $regra }}' => '{!! $validacao['mensagem'] !!}',
@endif
@endforeach
@endforeach
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
@foreach ($filhas as $filha)
        if ($this->model->{{ $filha->model }}S->count() > 0) {
            return '{{ $titulo }} sendo utilizada em "{{ $filha->model }}"!';
        }
        
@endforeach
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = {{ $model }}::query();
        
        // Filtros
@foreach ($cols as $col) <?php if ($col->column_name == 'inativo') continue; ?>
        if (!empty($filters['{{ $col->column_name }}'])) {
@if ($col->udt_name == 'varchar')
            $qry->palavras('{{ $col->column_name }}', $filters['{{ $col->column_name }}']);
@else
            $qry->where('{{ $col->column_name }}', '=', $filters['{{ $col->column_name }}']);
@endif
        }

@endforeach
        
        $count = $qry->count();
    
        switch ($filters['inativo']) {
            case 2: //Inativos
                $qry = $qry->inativo();
                break;

            case 9: //Todos
                break;

            case 1: //Ativos
            default:
                $qry = $qry->ativo();
                break;
        }
        
        // Paginacao
        if (!empty($start)) {
            $qry->offset($start);
        }
        if (!empty($length)) {
            $qry->limit($length);
        }
        
        // Ordenacao
        foreach ($sort as $s) {
            $qry->orderBy($s['column'], $s['dir']);
        }
        
        // Registros
        return [
            'recordsFiltered' => $count
            , 'recordsTotal' => {{ $model }}::count()
            , 'data' => $qry->get()
        ];
        
    }
    
}
