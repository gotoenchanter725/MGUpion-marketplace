<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimentoTipo;

use MGLara\Repositories\EstoqueMesRepository;

use Illuminate\Support\Facades\DB;

/**
 * @property $codestoquemes bigint
 * @property $ciclo bigint
 */

class EstoqueCalculaCustoMedio extends Job implements ShouldQueue
{
    
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $codestoquemes;
    protected $ciclo;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codestoquemes, $ciclo = 0)
    {
        $this->codestoquemes = $codestoquemes;
        $this->ciclo = $ciclo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repo = new EstoqueMesRepository();
        $mes = $repo->findOrFail($this->codestoquemes);
        $repo->calculaCustoMedio($mes, $this->ciclo);
    }
}
