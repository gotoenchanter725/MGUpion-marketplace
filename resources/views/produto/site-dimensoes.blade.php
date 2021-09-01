{!! Form::hidden("codprodutoembalagem[$codprodutoembalagem]", $codprodutoembalagem, ['class'=> 'form-control', 'id'=>"codprodutoembalagem-$codprodutoembalagem", 'rows'=>'10']) !!}
<div class="card">
  <h4 class="card-header">
    {{ $data['unidademedida'][$codprodutoembalagem] or '' }}
    @if (!empty($data['quantidade'][$codprodutoembalagem]))
    C/{{ formataNumero($data['quantidade'][$codprodutoembalagem], 0) }}
    @endif
  </h4>
  <div class="card-block">
    <div class='row'>

      <!-- peso -->
      <div class='col-md-2'>
        <fieldset>
          <div class="checkbox checkbox-primary pull-left">
            {!! Form::checkbox("vendesite[$codprodutoembalagem]", true, $data['vendesite'][$codprodutoembalagem]??0, ['class'=> 'form-control', 'id'=>'site-$codprodutoembalagem']) !!}
            {!! Form::label("vendesite-$codprodutoembalagem", 'Vendas via Site') !!}
            &nbsp;
          </div>
        </fieldset>
        <fieldset>
          <label id="descricao-peso-{{ $codprodutoembalagem }}" for='peso-{{ $codprodutoembalagem }}'>
              Peso
          </label>
          <div class='input-group'>
            {!! Form::number("peso[$codprodutoembalagem]", $data['peso'][$codprodutoembalagem]??null, ['class'=> 'form-control text-right peso', 'id'=>"peso-$codprodutoembalagem", 'step'=>0.0001, 'min'=>0.0001, 'max'=>999.9999, 'data-codprodutoembalagem'=>$codprodutoembalagem]) !!}
            <div class="input-group-addon">KG</div>
          </div>
        
        <img id="imagem-peso-{{ $codprodutoembalagem }}" class="img-fluid" style="">
        </fieldset>
      </div>
      
      <!-- dimensoes -->
      <div class="col-md-2">
        <fieldset class="form-group">
          {!! Form::label("altura-$codprodutoembalagem", 'Altura') !!}
          <div class='input-group'>
            {!! Form::number("altura[$codprodutoembalagem]", $data['altura'][$codprodutoembalagem]??null, ['class'=> 'form-control text-right altura', 'id'=>"altura-$codprodutoembalagem", 'step'=>0.01, 'min'=>0.01, 'max'=>999999.99, 'data-codprodutoembalagem'=>$codprodutoembalagem]) !!}
            <div class="input-group-addon">CM</div>
          </div>
        </fieldset>
        <fieldset class="form-group">
          {!! Form::label("largura-$codprodutoembalagem", 'Largura') !!}
          <div class='input-group'>
            {!! Form::number("largura[$codprodutoembalagem]", $data['largura'][$codprodutoembalagem]??null, ['class'=> 'form-control text-right largura', 'id'=>"largura-$codprodutoembalagem", 'step'=>0.01, 'min'=>0.01, 'max'=>999999.99, 'data-codprodutoembalagem'=>$codprodutoembalagem]) !!}
            <div class="input-group-addon">CM</div>
          </div>
        </fieldset>
        <fieldset class="form-group">
          {!! Form::label("profundidade-$codprodutoembalagem", 'Profundidade') !!}
          <div class='input-group'>
            {!! Form::number("profundidade[$codprodutoembalagem]", $data['profundidade'][$codprodutoembalagem]??null, ['class'=> 'form-control text-right profundidade', 'id'=>"profundidade-$codprodutoembalagem", 'step'=>0.01, 'min'=>0.01, 'max'=>999999.99, 'data-codprodutoembalagem'=>$codprodutoembalagem]) !!}
            <div class="input-group-addon">CM</div>
          </div>
        </fieldset>
        <fieldset class="form-group">
        <input class="form-control aproximacao" id="aproximacao-{{ $codprodutoembalagem }}" type="range" min="0.1" step="0.1" max="2" value="1" data-codprodutoembalagem='{{ $codprodutoembalagem}}'>
        </fieldset>
      </div>

      <!-- desenho dimensoes -->
      <div class='col-md-3' id='div-canvas-{{ $codprodutoembalagem }}'>
        <canvas id='canvas-{{ $codprodutoembalagem }}' style='min-height: 250px'></canvas>
      </div>

      <!-- descricao -->
      <div class='col-md-5'>
        <fieldset class="form-group">
          {!! Form::label("descricaosite-$codprodutoembalagem", 'Descrição Completa') !!}
          {!! Form::textarea("descricaosite[$codprodutoembalagem]", $data['descricaosite'][$codprodutoembalagem]??null, ['class'=> 'form-control descricaosite', 'id'=>"descricaosite-$codprodutoembalagem", 'rows'=>'10']) !!}
        </fieldset>
      </div>
      
    </div>
    <div class='clearfix'></div>
  </div>
</div>