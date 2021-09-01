<?php echo "<?php\n"; ?>

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\{{ $model }}Repository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property {{ $model }}Repository $repository 
 */
class {{ $model }}Controller extends Controller
{

    public function __construct({{ $model }}Repository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('{{ $titulo }}');
        $this->bc->addItem('{{ $titulo }}', url('{{ $url }}'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        // Permissao
        $this->repository->authorize('listing');
        
        // Breadcrumb
        $this->bc->addItem('Listagem');
        
        // Filtro da listagem
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'filtros' => [
                    'inativo' => 1,
                ],
                'order' => [[
                    'column' => 0,
                    'dir' => 'DESC',
                ]],
            ];
        }
        
        
        // retorna View
        return view('{{ $url }}.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }

    /**
     * Monta json para alimentar a Datatable do index
     * 
     * @param Request $request
     * @return json
     */
    public function datatable(Request $request) {
        
        // Autorizacao
        $this->repository->authorize('listing');

        // Grava Filtro para montar o formulario da proxima vez que o index for carregado
        $this->setFiltro([
            'filtros' => $request['filtros'],
            'order' => $request['order'],
        ]);
        
        // Ordenacao
        $columns[0] = '{{ $instancia_model->getKeyName() }}';
        $columns[1] = 'inativo';
        $columns[2] = '{{ $instancia_model->getKeyName() }}';
        $columns[3] = '{{ $coluna_titulo }}';
<?php $i=3; ?>
@foreach ($cols_listagem as $col)<?php $i++; ?>
        $columns[{{ $i }}] = '{{ $col->column_name }}';
@endforeach

        $sort = [];
        if (!empty($request['order'])) {
            foreach ($request['order'] as $order) {
                $sort[] = [
                    'column' => $columns[$order['column']],
                    'dir' => $order['dir'],
                ];
            }
        }

        // Pega listagem dos registros
        $regs = $this->repository->listing($request['filtros'], $sort, $request['start'], $request['length']);
        
        // Monta Totais
        $recordsTotal = $regs['recordsTotal'];
        $recordsFiltered = $regs['recordsFiltered'];
        
        // Formata registros para exibir no data table
        $data = [];
        foreach ($regs['data'] as $reg) {
            $data[] = [
                url('{{ $url }}', $reg->{{ $instancia_model->getKeyName() }}),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->{{ $instancia_model->getKeyName() }}),
                $reg->{{ $coluna_titulo }},
@foreach ($cols_listagem as $col)
                $reg->{{ $col->column_name }},
@endforeach
            ];
        }
        
        // Envelopa os dados no formato do data table
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorna o JSON
        return collect($ret);
        
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // cria um registro em branco
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('{{ $url }}.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parent::store($request);
        
        // Mensagem de registro criado
        Session::flash('flash_create', '{{ $titulo }} criado!');
        
        // redireciona para o view
        return redirect("{{ $url }}/{$this->repository->model->{{ $instancia_model->getKeyName() }}}");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // busca registro
        $this->repository->findOrFail($id);
        
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->{{ $coluna_titulo }});
        $this->bc->header = $this->repository->model->{{ $coluna_titulo }};
        
        // retorna show
        return view('{{ $url }}.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // busca regstro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->{{ $coluna_titulo }}, url('{{ $url }}', $this->repository->model->{{ $instancia_model->getKeyName() }}));
        $this->bc->header = $this->repository->model->{{ $coluna_titulo }};
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('{{ $url }}.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        parent::update($request, $id);
        
        // mensagem re registro criado
        Session::flash('flash_update', '{{ $titulo }} alterado!');
        
        // redireciona para view
        return redirect("{{ $url }}/{$this->repository->model->{{ $instancia_model->getKeyName() }}}"); 
    }
    
}
