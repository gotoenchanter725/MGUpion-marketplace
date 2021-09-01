<?php
use MGLara\Repositories\PessoaRepository;
use MGLara\Repositories\FilialRepository;
use MGLara\Repositories\CargoRepository;
use Collective\Html\FormBuilder;

$cargos = new CargoRepository();
$cargos = $cargos->model->orderBy('cargo')->pluck('cargo', 'codcargo')->prepend('', '');

$filiais = new FilialRepository();
$filiais = $filiais->model->orderBy('codfilial')->get();

$pessoas = new PessoaRepository();
$pessoas = $pessoas->model->where('codgrupocliente', 8)
                ->where('vendedor', true)
                ->whereNull('inativo')
                ->orderBy('fantasia')
                ->pluck('fantasia', 'codpessoa')
                ->prepend('', '');
?>
<fieldset class="form-group">
    {!! Form::label('meta[periodoinicial]', 'Período') !!}
    <div class="input-group">
        {!! Form::datetimeLocal('meta[periodoinicial]', $model->periodoinicial, ['class' => 'form-control text-center', 'id' => 'meta[periodoinicial]', 'placeholder' => 'De', 'style'=>'width:200px; margin-right:10px']) !!}
        {!! Form::datetimeLocal('meta[periodofinal]', $model->periodofinal, ['class' => 'form-control text-center', 'id' => 'meta[periodofinal]', 'placeholder' => 'Até', 'style'=>'width:200px;']) !!}
    </div>
</fieldset>

<div class="row">
    <div class="col-md-4">
        <fieldset class="form-group">
            {!! Form::label('meta[premioprimeirovendedorfilial]', 'Prêmio Melhor Vendedor') !!}
            <div class="input-group">
                <div class="input-group-addon">R$</div>
                {!! Form::number('meta[premioprimeirovendedorfilial]', null, ['class' => 'form-control text-right',  'id'=> 'meta[premioprimeirovendedorfilial]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
            </div>
        </fieldset>
    </div>    
    <div class="col-md-4">
        <fieldset class="form-group">
            {!! Form::label('meta[percentualcomissaovendedor]', 'Comissão') !!}
            <div class="input-group">
                {!! Form::number('meta[percentualcomissaovendedor]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaovendedor]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
                <div class="input-group-addon">%</div>
            </div>
        </fieldset>
    </div>    
    <div class="col-md-4">
        <fieldset class="form-group">
            {!! Form::label('meta[percentualcomissaovendedormeta]', 'Prêmio Meta Vendedor') !!}
            <div class="input-group">
                {!! Form::number('meta[percentualcomissaovendedormeta]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaovendedormeta]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
                <div class="input-group-addon">%</div>
            </div>
        </fieldset>
    </div>    
</div>

<div class="row">
    <div class="col-md-4">
        <fieldset class="form-group">
            {!! Form::label('meta[percentualcomissaosubgerentemeta]', 'Prêmio Meta Sub-Gerente') !!}
            <div class="input-group">
                {!! Form::number('meta[percentualcomissaosubgerentemeta]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaosubgerentemeta]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
                <div class="input-group-addon">%</div>
            </div>
        </fieldset>
    </div>
    <div class="col-md-4">
        <fieldset class="form-group">
            {!! Form::label('meta[percentualcomissaoxerox]', 'Comissao Xerox') !!}
                <div class="input-group">
                    {!! Form::number('meta[percentualcomissaoxerox]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaoxerox]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
                    <div class="input-group-addon">%</div>
                </div>
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <fieldset class="form-group">
            {!! Form::label('meta[observacoes]', 'Observações') !!}
            {!! Form::textarea('meta[observacoes]', null, ['class'=> 'form-control', 'id'=>'meta[observacoes]', 'rows'=>'3']) !!}
        </fieldset>
    </div>    
</div>

<div class="row">
    <div class="col-xs-2">
        <ul class="nav nav-tabs tabs-left">
            @foreach($filiais as $filial)
            <li class="nav-item"><a class="nav-link" href="#tab-filial-{{$filial->codfilial}}" data-toggle="tab">{{$filial->filial}}</a></li>
            @endforeach
        </ul>
    </div>
    <div class="col-xs-10">
        <div class="tab-content">
            @foreach($filiais as $filial)
            <div class="tab-pane" id="tab-filial-{{$filial->codfilial}}">
                <h4>{{ $filial->filial }}</h4>
                <fieldset class="form-group">
                    <div class="checkbox checkbox-primary">
                        {!! Form::checkbox("metafilial[$filial->codfilial][controla]", true, null, ['class'=> 'form-control controla', 'data-filial' => $filial->codfilial, 'id'=>'controla']) !!}
                        {!! Form::label('controla', 'Controla') !!}
                    </div>                    
                </fieldset>
                
                <div id="dados-filial-{{$filial->codfilial}}" @if(!isset($model['metafilial'][$filial->codfilial]['controla'])) style="display: none" @endif>            
                    {!! Form::hidden("metafilial[$filial->codfilial][codmetafilial]", null, ['class' => 'form-control']) !!}
                    <div class="row">
                        <div class="col-md-4">
                            <fieldset class="form-group">
                                {!! Form::label("metafilial[$filial->codfilial][valormetafilial]", 'Meta Filial') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">R$</div>
                                    {!! Form::number("metafilial[$filial->codfilial][valormetafilial]", null, ['class' => 'form-control text-right',  'id'=>"valormetafilial_$filial->codfilial", 'placeholder' => '', 'step'=>'0.01']) !!}
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <fieldset class="form-group">
                                {!! Form::label("metafilial[$filial->codfilial][valormetavendedor]", 'Meta Vendedor') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">R$</div>
                                    {!! Form::number("metafilial[$filial->codfilial][valormetavendedor]", null, ['class' => 'form-control text-right',  'id'=>"valormetavendedor_$filial->codfilial", 'placeholder' => '', 'step'=>'0.01']) !!}
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <fieldset class="form-group">
                                {!! Form::label("metafilial[$filial->codfilial][observacoes]", 'Observações') !!}
                                {!! Form::textarea("metafilial[$filial->codfilial][observacoes]", null, ['class'=> 'form-control', 'id'=>"metafilial[$filial->codfilial][observacoes]", 'rows'=>'3']) !!}
                            </fieldset>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <fieldset class="form-group">
                            <div class="row">
                            <p class="col-md-12" style="padding-left: 20px; margin-bottom: 20px">
                                <a class="btn btn-primary adicionar-pessoas" data-filial="{{ $filial->codfilial }}">Adicionar Colaborador</a>
                            </p>
                            <div  id="add-{{ $filial->codfilial }}">
                                <div class="cargo-pessoa cargo-pessoa-{{ $filial->codfilial }} col-md-12 invisible">
                                    <div class="col-md-4">                    
                                    {!! Form::select("pessoa", $pessoas, null, ['class'=> 'form-control adicionar-pessoa', 'data-filial'=>$filial->codfilial]) !!}
                                    </div>
                                    <div class="col-md-3">                    
                                    {!! Form::select("cargo", $cargos, null, ['class'=> 'form-control adicionar-cargo', 'data-filial'=>$filial->codfilial]) !!}
                                    </div>
                                    <a class="btn text-danger pull-left remover-pessoa"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                            @if(isset($model['metafilial'][$filial->codfilial]))
                                @foreach($model['metafilial'][$filial->codfilial]['pessoas'] as $pessoa)
                                <div class="cargo-pessoa col-md-12">
                                    <div class="col-md-4">
                                    {!! Form::select("metafilial[$filial->codfilial][pessoas][$pessoa[codpessoa]][codpessoa]", $pessoas, null, ['class'=> 'form-control select pessoa', 'style'=>"width: 100%;", 'data-filial'=>$filial->codfilial]) !!}
                                    </div>
                                    <div class="col-md-3">
                                    {!! Form::select("metafilial[$filial->codfilial][pessoas][$pessoa[codpessoa]][codcargo]", $cargos, null, ['class'=> 'form-control select cargo', 'style'=>"width: 100%", 'data-filial'=>$filial->codfilial]) !!}
                                    </div>
                                    {!! Form::hidden("metafilial[$filial->codfilial][pessoas][$pessoa[codpessoa]][codmetafilialpessoa]", null, ['class'=> 'form-control', ]) !!}
                                    <a class="btn text-danger pull-left remover-pessoa"><i class="fa fa-trash"></i></a>
                                </div>
                                @endforeach
                            @endif
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            @endforeach  
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
                </div>
            </div>
        </div>
    </div>  
</div>

@section('inscript')
<link href="{{ URL::asset('public/assets/plugins/bootstrap-vertical-tabs/tabs.css') }}" rel="stylesheet">
<style>
.pessoas-metafilial {
    margin-bottom: 10px;
}
.cargo-pessoa {
    margin-bottom: 10px;
}
.tabs-left li a {
    text-align: right; 
}
.invisible {
    display: none;
}
</style>
<script type="text/javascript">
function removePessoasDuplicadas(array, prop) {
     var novoArray = [];
     var objeto  = {};
 
     for (var i in array) {
         objeto[array[i][prop]] = array[i];
     }
 
     for (i in objeto) {
         novoArray.push(objeto[i]);
     }
 
     return novoArray;
}    

function validaPessoas() {
    var pessoas = [];
    $("select.select.pessoa").each( function(index) {
        pessoas.push({
            'codpessoa': $(this).val(),
            'pessoa': $('option:selected',this).text()
        });
    });
    
    var pessoasFiltradas = removePessoasDuplicadas(pessoas, "codpessoa");
    var pessoasRepetidas = [];
    
    jQuery.grep(pessoas, function(el) {
        if (jQuery.inArray(el, pessoasFiltradas) === -1) pessoasRepetidas.push(el);
    });
    
    if(pessoasRepetidas.length === 1) {
        var pessoaMensagem = pessoasRepetidas[0].pessoa;
        var mensagem = pessoaMensagem +" foi lançada(o) mais de uma vez!";
    } else {
        var pessoasMensagem = [];
        for (var i = 0; i < pessoasRepetidas.length; i++) {
           pessoasMensagem.push(pessoasRepetidas[i].pessoa);
        }            
        var pessoaMensagem = pessoasMensagem.toString();
        var mensagem = pessoaMensagem.replace(',',', ') +" foram lançadas(os) mais de uma vez!";
    }

    if(pessoas.length === pessoasFiltradas.length){
        return true;
    } else {
        bootbox.alert(mensagem);
        return false;
    }
}

$(document).ready(function() {
    $('#form-meta').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        if(validaPessoas()) {
            swal({
              title: "Tem certeza que deseja salvar?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              closeOnConfirm: false,
              closeOnCancel: true
            },
            function(isConfirm){
              if (isConfirm) {
                currentForm.submit();
              } 
            });       
        }       
    });

    $( "ul.nav-tabs li:first-child a, div.tab-content div.tab-pane:first-child").addClass('active');

    $('.select').select2({
        allowClear: true,
        closeOnSelect: true        
    });


    $('.controla').on('change', function(e) {
        e.preventDefault();
        var filial = $(this).data("filial");
        if($(this).is(":checked")) {
            $('#dados-filial-'+filial).slideDown('slow');
            $('#valormetavendedor_'+filial).attr('required', true);
            $('#valormetafilial_'+filial).attr('required', true);
        } else {
            $('#dados-filial-'+filial).slideUp('slow');
            $('#valormetavendedor_'+filial).attr('required', false);
            $('#valormetafilial_'+filial).attr('required', false);
        }
    });

    $('.adicionar-pessoas').on('click', function(e) {
        e.preventDefault();
        $('.select').select2("destroy");
        var filial = $(this).data("filial");
        var seletor = '.cargo-pessoa-'+filial;
        var div = '#add-'+filial;
        
        $(seletor).first().clone(true, true).appendTo(div);
        $(seletor).last().removeClass('invisible').addClass('clone');
        $(seletor+'.clone select').addClass('select');
        
        $('.select').select2({
            allowClear: true,
            closeOnSelect: true        
        });

        var nome = $(seletor+'.clone select').prev().attr('id');
        $(seletor+'.clone:last-child select').attr('name', nome).attr('required', true);
        $(seletor+'.clone:last-child select:last-child').attr('name', nome+'1').attr('required', true);


    });    

    $('.adicionar-pessoa').on('change', function () {
        var filial = $(this).data('filial');
        var pessoa = $(this).val();
        $(this).attr('name', 'metafilial['+filial+'][pessoas]['+pessoa+'][codpessoa]');
        $(this).parent()
            .next('.col-md-3')
            .children('select')
            .attr('name', 'metafilial['+filial+'][pessoas]['+pessoa+'][codcargo]');
    });

    $('.remover-pessoa').on('click', function() {
        $(this).parent().remove();
    });

});
</script>
@endsection
