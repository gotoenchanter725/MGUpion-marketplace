    <div class="timeline">
        <article class="timeline-item alt">
            <div class="text-xs-right">
                <div class="time-show first">
                    <a href="#" class="btn btn-custom w-lg">Conferências</a>
                </div>
            </div>
        </article>
        @foreach ($es->EstoqueSaldoConferenciaS()->orderBy('criacao', 'desc')->limit(200)->get() as $i => $conf)
        <?php
            $class = (empty($conf->inativo)?'primary':'danger')
        ?>
        <article class="timeline-item {{ ($i % 2 == 0)?'alt':'' }}">
            <div class="timeline-desk">
                <div class="panel">
                    <div class="timeline-box">
                        <span class="arrow{{ ($i % 2 == 0)?'-alt':'' }}"></span>
                        <span class="timeline-icon bg-{{ $class }}"><i class="zmdi zmdi-circle"></i></span>
                        <h4 class="text-{{ $class }}">
                          @if (!empty($conf->inativo))
                            Conferência Inativada
                            <a href="{{ url('estoque-saldo-conferencia', $conf->codestoquesaldoconferencia) }}">
                              <s>{{ $conf->criacao->diffForHumans() }}</s>
                            </a>
                          @else
                            <a href="{{ url('estoque-saldo-conferencia', $conf->codestoquesaldoconferencia) }}">
                              {{ $conf->criacao->diffForHumans() }}
                            </a>
                          @endif
                        </h4>
                        <p class="timeline-date text-muted">
                          <small>
                            Conferido por 
                            <a href="{{ url('usuario', $conf->codusuariocriacao) }}">{{ $conf->UsuarioCriacao->usuario }}</a> 
                            em 
                            {{ formataData($conf->criacao, 'L') }}
                          </small>
                        </p>
                        <p>
                          Informado 
                          <span class="text-{{ $class }}">{{ formataNumero($conf->quantidadeinformada, 3) }}</span>
                          (<s>{{ formataNumero($conf->quantidadesistema, 3) }}</s>),
                          custando 
                          <span class="text-{{ $class }}">{{ formataNumero($conf->customedioinformado, 6) }}</span>
                          (<s>{{ formataNumero($conf->customediosistema, 6) }}</s>).
                        </p>
                        <p>
                          Ajuste lançado em 
                          {{ formataData($conf->data, 'L') }}.
                        </p>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>
