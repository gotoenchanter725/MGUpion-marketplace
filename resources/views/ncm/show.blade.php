@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class='card'>
            <h4 class="card-header">Detalhes</h4>
            <div class='card-block'>
                <table class="detail-view table table-striped table-condensed"> 
                  <tbody>
                    <tr>
                      <th>Descrição</th>
                      <td>{{ $model->descricao }}</td>
                    </tr>
                    @if(!empty($model->codncmpai))
                    <tr> 
                      <th>Filho de</th> 
                      <td>
                          <a href="{{ url("ncm/{$model->Ncm->codncm}") }}">
                              {{ formataNcm($model->Ncm->ncm) }}
                          </a>
                      </td>
                    </tr>
                    @endif
                  </tbody> 
                </table>
            </div>
        </div>      
    </div>    
</div>
<div class="row">
    <div class="col-md-4">
        <div class='card'>
            <h4 class="card-header">ICMS/ST <small>Regulamento de ICMS Substituição Tributária do Estado de Mato Grosso - Anexo X</small></h4>
            <div class='card-block'>        
            @foreach($model->regulamentoIcmsStMtsDisponiveis() as $item)
                <table class="detail-view table table-striped table-condensed"> 
                  <tbody>
                    <tr>
                      <th>#</th>
                      <td>{{ $item['codregulamentoicmsstmt'] }}</td>
                    </tr>
                    <tr> 
                      <th>Subitem</th> 
                      <td>{{ $item['subitem'] }}</td>
                    </tr>
                    <tr> 
                      <th>Descrição</th> 
                      <td>{{ $item['descricao'] }}</td>
                    </tr>
                    <tr> 
                      <th>NCM</th> 
                      <td>{{ formataNcm($item['ncm']) }}</td>
                    </tr>
                    <tr> 
                      <th>NCM Exceto</th> 
                      <td>{{ $item['ncmexceto'] }}</td>
                    </tr>
                    <tr> 
                      <th>ICMS ST Sul</th> 
                      <td>{{ $item['icmsstsul'] }}</td>
                    </tr>
                    <tr> 
                      <th>ICMS ST Norte</th> 
                      <td>{{ $item['icmsstnorte'] }}</td>
                    </tr>
                  </tbody> 
                </table>
            @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class='card'>
            <h4 class="card-header">CEST <small>Código Especificador da Substituição Tributária - Anexo I</small></h4>
            <div class='card-block'>        
            @foreach($model->cestsDisponiveis() as $cest)
                <table class="detail-view table table-striped table-condensed"> 
                  <tbody>
                    <tr>
                      <th>#</th>
                      <td>{{ $cest['codcest'] }}</td>
                    </tr>
                    <tr> 
                      <th>CEST</th> 
                      <td>{{ formataCest($cest['cest']) }}</td>
                    </tr>
                    <tr> 
                      <th>NCM</th> 
                      <td>{{ formataNcm($cest['ncm']) }}</td>
                    </tr>
                    <tr> 
                      <th>Descrição</th> 
                      <td>{{ $cest['descricao'] }}</td>
                    </tr>
                  </tbody> 
                </table>        
            @endforeach()
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class='card'>
            <h4 class="card-header">IBPT <small>Instituto Brasileiro de Planejamento e Tributação</small></h4>
            <div class='card-block'>        
            @foreach ($model->IbptaxS as $item)
                <table class="detail-view table table-striped table-condensed"> 
                  <tbody>
                    <tr>
                      <th>#</th>
                      <td>{{ $item->codibptax }}</td>
                    </tr>
                    <tr> 
                      <th>Código</th> 
                      <td>{{ formataNcm($item->codigo) }}</td>
                    </tr>
                    <tr> 
                      <th>Ex</th> 
                      <td>{{ $item->ex }}</td>
                    </tr>
                    <tr> 
                      <th>Tipo</th> 
                      <td>{{ $item->tipo }}</td>
                    </tr>
                    <tr> 
                      <th>Descrição</th> 
                      <td>{{ $item->descricao }}</td>
                    </tr>
                    <tr> 
                      <th>Nacional Federal</th> 
                      <td>{{ formataNumero($item->nacionalfederal) }}%</td>
                    </tr>
                    <tr> 
                      <th>Importados Federal</th> 
                      <td>{{ formataNumero($item->importadosfederal) }}%</td>
                    </tr>
                    <tr> 
                      <th>Estadual</th> 
                      <td>{{ formataNumero($item->estadual) }}%</td>
                    </tr>
                    <tr> 
                      <th>Municipal</th> 
                      <td>{{ formataNumero($item->municipal) }}%</td>
                    </tr>
                    <tr> 
                      <th>Vigencia Inicio</th> 
                      <td>{{ formataData($item->vigenciainicio) }}</td>
                    </tr>
                    <tr> 
                      <th>Vigencia Fim</th> 
                      <td>{{ formataData($item->vigenciafim) }}</td>
                    </tr>
                    <tr> 
                      <th>Chave</th> 
                      <td>{{ $item->chave }}</td>
                    </tr>
                    <tr> 
                      <th>Versão</th> 
                      <td>{{ $item->versao }}</td>
                    </tr>
                    <tr> 
                      <th>Fonte</th> 
                      <td>{{ $item->fonte }}</td>
                    </tr>            
                  </tbody> 
                </table>
            @endforeach
            </div>
        </div>
    </div>
</div>
@if (count($filhos) > 0)
<div class="row">
    <div class="col-lg-12">
        <div class='card'>
            <h4 class="card-header">Filhos</h4>
            <div class='card-block'>
                <table class="detail-view table table-striped table-condensed"> 
                  <tbody>
                      @foreach($filhos as $row)
                    <tr>
                      <td>
                        <a class="" href="{{ url("ncm/$row->codncm") }}">{{ formataNcm($row->ncm) }}</a>
                      </td>
                      <td>
                        <a href="{{ url("ncm/$row->codncm") }}">{{ $row->descricao}}</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody> 
                </table>
            </div>
        </div>      
    </div>    
</div>
@endif

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("ncm/$model->codncm/edit") }}"><i class="fa fa-pencil"></i></a>
    @if (empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("ncm/$model->codncm/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->descricao }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("ncm/$model->codncm/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->descricao }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("ncm/$model->codncm") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->descricao }}'?" data-after="location.replace('{{ url('ncm') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')

@endsection
@stop