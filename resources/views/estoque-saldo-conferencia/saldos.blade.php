
<div class='row'>
  <div class="card">
    <h4 class="card-header">Informe o Saldo Conferido</h4>
    <div class="card-block">
      <div class="card-text">
        {!! Form::model($data, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-saldo', 'route' => 'estoque-saldo-conferencia.store']) !!}
        {!! Form::hidden('codestoquelocal') !!}
        {!! Form::hidden('codprodutovariacao') !!}
        {!! Form::hidden('codestoquesaldo') !!}
        {!! Form::hidden('fiscal') !!}
        <div class="row">
            <div class="col-md-4">
                <fieldset class="form-group">
                    {!! Form::label('data', 'Data do ajuste') !!}
                    {!! Form::datetimeLocalMG('data', null, ['class'=> 'form-control text-center', 'id'=>'data', 'required' => 'required']) !!}
                </fieldset>
            </div>
            <div class="col-md-2">
                <fieldset class="form-group">
                    {!! Form::label('quantidadeinformada', 'Quantidade') !!}
                    {!! Form::number('quantidadeinformada', null, ['class'=> 'form-control text-right', 'id'=>'quantidadeinformada', 'step'=>0.001, 'min'=>0, 'required' => 'required']) !!}
                </fieldset>
            </div>
            <div class="col-md-2">
                <fieldset class="form-group">
                    {!! Form::label('customedioinformado', 'Custo') !!}
                    {!! Form::number('customedioinformado', null, ['class'=> 'form-control text-right', 'id'=>'customedioinformado', 'step'=>0.000001, 'min'=>0, 'required' => 'required']) !!}
                </fieldset>
            </div>
        </div>
        <div class='row'>
            <div class="col-md-2">
                <fieldset class="form-group">
                    {!! Form::label('corredor', 'Corredor') !!}
                    {!! Form::number('corredor', null, ['class'=> 'form-control text-center', 'placeholder' => 'Corredor', 'id'=>'corredor', 'step'=>1, 'min'=>1]) !!}
                </fieldset>
            </div>
            <div class="col-md-2">
                <fieldset class="form-group">
                    {!! Form::label('prateleira', 'Prateleira') !!}
                          {!! Form::number('prateleira', null, ['class'=> 'form-control text-center', 'placeholder' => 'Prateleira', 'id'=>'corredor', 'step'=>1, 'min'=>1]) !!}
                </fieldset>
            </div>
            <div class="col-md-2">
                <fieldset class="form-group">
                    {!! Form::label('coluna', 'Coluna') !!}
                          {!! Form::number('coluna', null, ['class'=> 'form-control text-center', 'placeholder' => 'Coluna', 'id'=>'corredor', 'step'=>1, 'min'=>1]) !!}
                </fieldset>
            </div>
            <div class="col-md-2">
                <fieldset class="form-group">
                    {!! Form::label('bloco', 'Bloco') !!}
                          {!! Form::number('bloco', null, ['class'=> 'form-control text-center', 'placeholder' => 'Bloco', 'id'=>'corredor', 'step'=>1, 'min'=>1]) !!}
                </fieldset>
            </div>
            <div class="col-md-1">
                <fieldset class="form-group">
                  {!! Form::label('btn-salvar', '&nbsp;') !!}
                  {!! Form::submit('Salvar', array('class' => 'btn btn-primary', 'id' => 'btn-salvar')) !!}
                </fieldset>
            </div>
            <div class="col-md-1">
                <fieldset class="form-group">
                  {!! Form::label('btn-cancelar', '&nbsp;') !!}
                      {!! Form::button('Cancelar', array('class' => 'btn btn-danger', 'id' => 'btn-cancelar')) !!}
                </fieldset>
            </div>
        </div>
        <div class="clearfix"></div>
        {!! Form::close() !!}   
      </div>
    </div>
  </div>
</div>

<div class='row'>
  <div class="card">
    <h4 class="card-header">Saldos</h4>
    <div class="card-block table-responsive">
        <div class="card-text">
          <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
              <tr>
                <th>
                  Variações
                </th>
                @foreach ($pivot['locais'] as $i_codestoquelocal => $local)
                  <th style='text-align: center'>
                    {{ $local['estoquelocal'] }}
                  </th>
               @endforeach          
               <th style="text-align: center">
                  Total
                </th>
              </tr>
            </thead>
            <tbody>
            @foreach ($pivot['variacoes'] as $i_codprodutovariacao => $variacao)
              <tr>
                <th>
                  {{ $variacao['variacao'] or '{Sem Variação}' }}
                </th>
                @foreach ($pivot['locais'] as $i_codestoquelocal => $local)
                    <?php
                        $class = ($i_codprodutovariacao == $codprodutovariacao && $i_codestoquelocal == $codestoquelocal)?'table-info':'';
                    ?>
                  <td style='text-align: right' class='{{ $class }}'>
                  @if (isset($pivot['data'][$i_codestoquelocal][$i_codprodutovariacao]))
                    @include ('estoque-saldo.includes.saldo', ['model' => $pivot['data'][$i_codestoquelocal][$i_codprodutovariacao]])
                  @else
                    &nbsp;
                  @endif
                  </td>
                @endforeach
                <th style='text-align: right'>
                  {{ formataNumero($variacao['saldoquantidade'], 3) }}
                </th>
              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>
                  Total
                </th>
                @foreach ($pivot['locais'] as $i_codestoquelocal => $local)
                  <th style='text-align: right'>
                    {{ formataNumero($local['saldoquantidade'], 3) }}
                  </th>
               @endforeach          
               <th style="text-align: right">
                 {{ formataNumero($pivot['saldoquantidade'], 3) }}
               </th>
              </tr>
            </tfoot>
          </table>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function () {

    $('#quantidadeinformada').focus();

    $('#form-saldo').on("submit", function(e) {
        e.preventDefault();
        salva();
    });
    
    $('#btn-cancelar').click(function (e) {
        limpaFiltro();
    });
    
});
</script>
