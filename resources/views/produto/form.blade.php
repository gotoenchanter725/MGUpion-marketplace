<?php
if(!empty($model->codsubgrupoproduto)){
    $model->codfamiliaproduto = $model->SubGrupoProduto->GrupoProduto->codfamiliaproduto;
    $model->codgrupoproduto = $model->SubGrupoProduto->GrupoProduto->codgrupoproduto;
}
?>
<div class="row">
    <div class='col-md-5'>
        <div class="row">  
            <fieldset class="form-group col-md-6">
                {!! Form::label('codsecaoproduto', 'Seção') !!}
                {!! Form::select2SecaoProduto('codsecaoproduto', null, ['required' => true, 'class'=> 'form-control', 'id' => 'codsecaoproduto', 'style'=>'width:100%', 'placeholder' => 'Seção']) !!}

            </fieldset>

            <fieldset class="form-group col-md-6">
                {!! Form::label('codfamiliaproduto', 'Família') !!}
                {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codfamiliaproduto', 'style'=>'width:100%', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto']) !!}
            </fieldset>
        </div>
        <div class="row">
            <fieldset class="form-group col-md-6">
                {!! Form::label('codgrupoproduto', 'Grupo Produto') !!}
                {!! Form::select2GrupoProduto('codgrupoproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codgrupoproduto', 'style'=>'width:100%', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto']) !!}
            </fieldset>

            <fieldset class="form-group col-md-6">
                {!! Form::label('codsubgrupoproduto', 'Sub Grupo') !!}
                {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codsubgrupoproduto', 'codgrupoproduto'=>'codgrupoproduto']) !!}        
            </fieldset>
        </div>
        <fieldset class="form-group">
            {!! Form::label('codncm', 'NCM') !!}
            {!! Form::select2Ncm('codncm', null, ['required' => true, 'class' => 'form-control','id'=>'codncm', 'style'=>'width:100%', 'placeholder' => 'NCM']) !!}
        </fieldset>
        
        <fieldset class="form-group">
            {!! Form::label('codtributacao', 'Tributação') !!}
            {!! Form::select2Tributacao('codtributacao', null, ['required' => true, 'placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao', 'style'=>'width:100%']) !!}
        </fieldset>

        <fieldset class="form-group">
            {!! Form::label('codcest', 'CEST') !!}
            {!! Form::select2Cest('codcest', null, ['class' => 'form-control','id'=>'codcest', 'style'=>'width:100%', 'placeholder' => 'CEST', 'codncm'=>'codncm']) !!}
        </fieldset>
    </div>

    <div class='col-md-7'>
        <div class="row">
            <fieldset class="form-group col-md-6">
                {!! Form::label('codtipoproduto', 'Tipo') !!}
                {!! Form::select2TipoProduto('codtipoproduto', null, ['required' => true,  'class'=> 'form-control', 'id' => 'codtipoproduto', 'style'=>'width:100%', 'placeholder'=>'Tipo']) !!}
            </fieldset>
            
            <fieldset class="form-group col-md-6">
                {!! Form::label('codmarca', 'Marca') !!}
                {!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%', 'required'=>true]) !!}    
            </fieldset>
        </div>
        <fieldset class="form-group">
            {!! Form::label('produto', 'Descrição') !!}
            <div id="produto-descricao">{!! Form::text('produto', null, ['class'=> 'typeahead form-control', 'id'=>'produto', 'data-provide'=>'typeahead', 'required'=>'true', 'autocomplete'=>'off']) !!}</div>
        </fieldset>

        <fieldset class="form-group">
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('referencia', 'Referência') !!}
                    {!! Form::text('referencia', null, ['class'=> 'form-control'], ['id'=>'referencia']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('codunidademedida', 'Preço') !!}
                    {!! Form::number('preco', null, ['required' => true, 'step' => 0.01, 'class'=> 'form-control text-right', 'id'=>'preco']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('codunidademedida', 'Unidade') !!}
                    {!! Form::select2UnidadeMedida('codunidademedida', null, ['required' => true,  'class'=> 'form-control', 'campo' => 'unidademedida', 'id' => 'codunidademedida']) !!}
                </div>
            </div>
        </fieldset>

        <fieldset class="form-group">
            {!! Form::label('observacoes', 'Observações') !!}
            {!! Form::textarea('observacoes', null, ['class'=> 'form-control', 'id'=>'observacoes', 'rows'=>'3']) !!}
        </fieldset>

        <fieldset class="form-group">
            <div class="checkbox checkbox-primary">
            {!! Form::checkbox('importado', true, null, ['class'=> 'form-control', 'id'=>'importado']) !!}
            {!! Form::label('importado', 'Importado') !!}
            </div>
        </fieldset>
        
    </div>
</div>
<fieldset class="form-group">
   {!! Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>


@section('inscript')
<script type="text/javascript" src="{{ URL::asset('public/assets/plugins/typeahead.js/typeahead.bundle.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/assets/plugins/typeahead.js/bloodhound.js') }}"></script>
<script src="{{ URL::asset('public/assets/js/setcase.js') }}"></script>
<style type="text/css">
.twitter-typeahead {
    width: 100%;
}
.popover {
    max-width: 100%;
    width: 70% !important;
}
.produtos-similares {
    list-style: none;
    padding: 5px 0;
}
.popover-title {
    display: none;
}
.typeahead,
.tt-query,
.tt-hint {
  width: 396px;
  height: 36px;
  line-height: 36px;
  border: 1px solid #ccc;
  outline: none;
}

.typeahead {
  background-color: #fff;
}

.typeahead:focus {

}

.tt-query {
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
  color: #999
}

.tt-menu {
  width: 422px;
  margin: 4px 0;
  padding: 4px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
  padding: 2px 10px;
  line-height: 24px;
}

.tt-suggestion:hover {
  cursor: pointer;
  color: #fff;
  background-color: #0097cf;
}

.tt-suggestion.tt-cursor {
  color: #fff;
  background-color: #0097cf;

}

.tt-suggestion p {
  margin: 0;
}

#custom-templates .empty-message {
  padding: 5px 10px;
 text-align: center;
}

#multiple-datasets .league-name {
  margin: 5px;
  padding: 3px 0;
  border-bottom: 1px solid #ccc;
}

#scrollable-dropdown-menu .tt-menu {
  max-height: 150px;
  overflow-y: auto;
}

#rtl-support .tt-menu {
  text-align: right;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    @if (!empty($model->codsubgrupoproduto))
        $('#codsecaoproduto').val({{ $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto }});
    @endif
    
    $('#form-principal').on("submit", function(e) {
        e.preventDefault();
        var currentForm = this;
        swal({
          title: "Tem certeza que deseja salvar?",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            currentForm.submit();
          } 
        });       
    });
    
    var codproduto = <?php echo (isset($model->codproduto) ? $model->codproduto:'""')?>;
    
    function descricaoProdutoTypeahead(codsubgrupoproduto, codproduto) {
        var produtoTypeahead = new Bloodhound({
            remote: {
                url: baseUrl + "/produto/typeahead?q=%QUERY%&codsubgrupoproduto="+ codsubgrupoproduto +"&codproduto="+codproduto,
                wildcard: '%QUERY%',
            },
            datumTokenizer: function(produtoTypeahead) {
                return Bloodhound.tokenizers.whitespace(produtoTypeahead.produto);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace       
        });
        
        $("#produto").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            source: produtoTypeahead.ttAdapter(),
            name: 'produtoTypeahead',
            displayKey: function(produtoTypeahead) {
              return produtoTypeahead.produto;        
            },            
            templates: {
                empty: [
                    '<p style="margin: 0; padding: 2px 8px;">Nenhum produto encontrado!</p>'
                ],
                header: [
                    //'<div class="list-group search-results-dropdown">'
                ],
                suggestion: function (data) {
                    return '<div>' + data.produto + '</div>'
                }
            },
            limit:14
        });
        
        $("#produto").on('typeahead:selected', function(e, data) {
            if($('#codsubgrupoproduto').val() == '') {
                $.getJSON(baseUrl + '/produto/popula-secao-produto', {
                    id: data.codproduto
                  }).done(function( data ) {
                      $("#codsubgrupoproduto").select2('val', 'id='+data.subgrupoproduto);
                      $("#codgrupoproduto").select2('val', 'id='+data.grupoproduto);
                      $("#codfamiliaproduto").select2('val', 'id='+data.familiaproduto);
                      $("#codsecaoproduto").select2('val', data.secaoproduto);
                });            
            }
        });        
    };

    $('#codsubgrupoproduto').change(function() {
        $('#produto').typeahead('destroy');
        var codsubgrupoproduto = $(this).val();
        descricaoProdutoTypeahead(codsubgrupoproduto, codproduto);
        $("#produto").Setcase();
    });
    
    descricaoProdutoTypeahead($('#codsubgrupoproduto').val(), codproduto);
    
    $("#produto").Setcase();

});
</script>
@endsection