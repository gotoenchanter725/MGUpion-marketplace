<div id="div-site">
  
  <!-- unidade -->
  <div class='card'>
    
    <!-- titulo -->
    <h3 class='card-header'>
      {{ $model->UnidadeMedida->unidademedida }}
      <small>
        <a class='btn btn-secondary btn-sm' href="{{ url("produto/{$model->codproduto}/site") }}" title="Informações p/ Site" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>      
      </small>
    </h3>

    <!-- dados da unidade -->
    <div class='card-block'>
      <dl>
        <div class='row'>
          <!-- coluna dados -->
          <div class='col-md-6'>

            <!-- site / codopencart -->
            <dt>
              Disponibilidade Site
            </dt>
            <dd>

              <!-- site -->
              @if ($model->site)
              <b class='text-success'>Disponível</b>
              @else
              <b class='text-danger'>Não disponível</b>
              @endif
              para visualização no site!

              <!-- codopencart -->
              @if (!empty($model->codopencart))
              (OpenCart ID #{{ $model->codopencart }})
              @endif          
              <br>

              <!-- Vendido via Site -->
              @if ($model->vendesite)
              <b class='text-success'>
                Vendido 
              </b>
              @else
              <b class='text-danger'>
                Não vendido 
              </b>
              @endif
              via site!
            </dd>

            <!-- Dimensoes -->
            @if (!empty($model->altura))
            <dt>
              Dimensões
            </dt>
            <dd>
              {{ formataNumero($model->altura, 2) }} CM X 
              {{ formataNumero($model->largura, 2) }} CM X 
              {{ formataNumero($model->profundidade, 2) }} CM = 
              <b>{{ formataNumero($model->altura * $model->largura * $model->profundidade, 2) }} CM<sup>3</sup></b>
            </dd>
            @endif

            <!-- Peso -->
            @if (!empty($model->peso))
            <dt>
              Peso
            </dt>
            <dd>
              {{ formataNumero($model->peso, 4) }} KG
            </dd>
            @endif

            <!-- Keywords -->
            @if (!empty($model->metakeywordsite))
            <dt>Meta Keywords</dt>
            <dd>
              {{ $model->metakeywordsite }}
            </dd>
            @endif

            <!-- Description -->
            @if (!empty($model->metadescriptionsite))
            <dt>Meta Description</dt>
            <dd>
              {{ $model->metadescriptionsite }}
            </dd>
            @endif

          </div>

          <!-- descicao completa -->
          @if (!empty($model->descricaosite))
          <div class='col-md-6'>
            <dt>Descrição Completa</dt>
            <dd>
              {!! nl2br($model->descricaosite) !!}
            </dd>
          </div>
          @endif

        </div>
      </dl>
    </div>
  </div>

  <!-- embalagens -->
  @foreach ($model->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe)
  <div class='card'>
    
    <!-- titulo -->
    <h3 class='card-header'>
      {{ $pe->UnidadeMedida->unidademedida }} C/{{ formataNumero($pe->quantidade, 0) }}
    </h3>

    <!-- dados da unidade -->
    <div class='card-block'>
      <dl>
        <div class='row'>
          <!-- coluna dados -->
          <div class='col-md-6'>

            <!-- site / codopencart -->
            <dt>
              Disponibilidade Site
            </dt>
            <dd>

              <!-- site -->
              @if ($pe->site)
              <b class='text-success'>Disponível</b>
              @else
              <b class='text-danger'>Não disponível</b>
              @endif
              para visualização no site!

              <!-- codopencart -->
              @if (!empty($pe->codopencart))
              (OpenCart ID #{{ $pe->codopencart }})
              @endif          
              <br>

              <!-- Vendido via Site -->
              @if ($pe->vendesite)
              <b class='text-success'>
                Vendido 
              </b>
              @else
              <b class='text-danger'>
                Não vendido 
              </b>
              @endif
              via site!
            </dd>

            <!-- Dimensoes -->
            @if (!empty($pe->altura))
            <dt>
              Dimensões
            </dt>
            <dd>
              {{ formataNumero($pe->altura, 2) }} CM X 
              {{ formataNumero($pe->largura, 2) }} CM X 
              {{ formataNumero($pe->profundidade, 2) }} CM = 
              <b>{{ formataNumero($pe->altura * $pe->largura * $pe->profundidade, 2) }} CM<sup>3</sup></b>
            </dd>
            @endif

            <!-- Peso -->
            @if (!empty($pe->peso))
            <dt>
              Peso
            </dt>
            <dd>
              {{ formataNumero($pe->peso, 4) }} KG
            </dd>
            @endif

            <!-- Keywords -->
            @if (!empty($pe->metakeywordsite))
            <dt>Meta Keywords</dt>
            <dd>
              {{ $pe->metakeywordsite }}
            </dd>
            @endif

            <!-- Description -->
            @if (!empty($pe->metadescriptionsite))
            <dt>Meta Description</dt>
            <dd>
              {{ $pe->metadescriptionsite }}
            </dd>
            @endif

          </div>

          <!-- descicao completa -->
          @if (!empty($pe->descricaosite))
          <div class='col-md-6'>
            <dt>Descrição Completa</dt>
            <dd>
              {!! nl2br($pe->descricaosite) !!}
            </dd>
          </div>
          @endif

        </div>
      </dl>
    </div>
  </div>
  @endforeach

</div>