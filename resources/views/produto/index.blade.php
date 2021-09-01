@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h4 class="card-header">Pesquisa</h4>
    <div class="card-block">
        <div class="card-text">
            {!! Form::model($filtro['filtros'], ['id' => 'form-search', 'autocomplete' => 'on'])!!}
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            {!! Form::label('codproduto', '#') !!}
                            {!! Form::number('codproduto', null, ['class' => 'form-control', 'placeholder' => '#']) !!}
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {!! Form::label('barras', 'Barras') !!}
                            {!! Form::text('barras', null, ['class' => 'form-control', 'placeholder' => 'Barras']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('produto', 'Descrição') !!}
                    {!! Form::text('produto', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('referencia', 'Referência') !!}
                    {!! Form::text('referencia', null, ['class' => 'form-control', 'placeholder' => 'Referência']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('preco_de', 'Preço') !!}
                    <div>
                        {!! Form::number('preco_de', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_de', 'placeholder' => 'De', 'style'=>'width:120px; margin-right:10px', 'step'=>'0.01']) !!}
                        {!! Form::number('preco_ate', null, ['class' => 'form-control text-right pull-left', 'id' => 'preco_ate', 'placeholder' => 'Até', 'style'=>'width:120px;', 'step'=>'0.01']) !!}
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            
            <div class="col-md-4">
            <div class="row">
                <div class="form-group col-md-6">
                    {!! Form::label('codmarca', 'Marca') !!}
                    {!! Form::select2Marca('codmarca', null, ['class' => 'form-control','id'=>'codmarca']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('codsecaoproduto', 'Seção') !!}
                    {!! Form::select2SecaoProduto('codsecaoproduto', null, ['required' => true, 'class'=> 'form-control', 'id' => 'codsecaoproduto', 'placeholder' => 'Seção']) !!}
                </div>
            </div>
                <div class="form-group">
                    {!! Form::label('codfamiliaproduto', 'Família') !!}
                    {!! Form::select2FamiliaProduto('codfamiliaproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codfamiliaproduto', 'placeholder' => 'Família', 'codsecaoproduto'=>'codsecaoproduto']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('codgrupoproduto', 'Grupo') !!}
                    {!! Form::select2GrupoProduto('codgrupoproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codgrupoproduto', 'placeholder' => 'Grupo', 'codfamiliaproduto'=>'codfamiliaproduto']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('codsubgrupoproduto', 'SubGrupo') !!}
                    {!! Form::select2SubGrupoProduto('codsubgrupoproduto', null, ['required' => true, 'class' => 'form-control','id'=>'codsubgrupoproduto', 'codgrupoproduto'=>'codgrupoproduto']) !!}        
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('codtributacao', 'Tributação') !!}
                    {!! Form::select2Tributacao('codtributacao', null, ['required' => true, 'placeholder'=>'Tributação',  'class'=> 'form-control', 'id' => 'codtributacao', 'style'=>'width:100%']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('codncm', 'NCM') !!}
                    {!! Form::select2Ncm('codncm', null, ['required' => true, 'class' => 'form-control','id'=>'codncm', 'style'=>'width:100%', 'placeholder' => 'NCM']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('criacao_de', 'Criação') !!}
                    <div>
                        {!! Form::date('criacao_de', null, ['class' => 'form-control pull-left', 'id' => 'criacao_de', 'placeholder' => 'De', 'style'=>'width:160px; margin-right:10px']) !!}
                        {!! Form::date('criacao_ate', null, ['class' => 'form-control pull-left', 'id' => 'criacao_ate', 'placeholder' => 'Até', 'style'=>'width:160px;']) !!}
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    {!! Form::label('alteracao_de', 'Alteração') !!}
                    <div>
                        {!! Form::date('alteracao_de', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_de', 'placeholder' => 'De', 'style'=>'width:160px; margin-right:10px']) !!}
                        {!! Form::date('alteracao_ate', null, ['class' => 'form-control pull-left', 'id' => 'alteracao_ate', 'placeholder' => 'Até', 'style'=>'width:160px;']) !!}
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('site', 'Site') !!}
                                {!! Form::select('site', ['' => '', 'true' => 'No Site', 'false' => 'Fora do Site'], null, ['id'=>'site', 'style'=>'width:100%;']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inativo" class="control-label">Ativos</label>
                            {!! Form::select2Inativo('inativo', null, ['class'=> 'form-control', 'id'=>'inativo']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class='clearfix'></div>
        </div>
    </div>
  </div>
</div>

<div class='card'>
    <div class='card-block table-responsive'>
        @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Imagem', 'Produto', 'Preços', 'Variações']])
        <div class='clearfix'></div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("produto/create") }}"><i class="fa fa-plus"></i></a> 
    <a class="btn btn-secondary btn-sm" href="#collapsePesquisa" data-toggle="collapse" aria-expanded="false" aria-controls="collapsePesquisa"><i class='fa fa-search'></i></a>
    
@endsection
@section('inscript')

    @include('layouts.includes.datatable.assets')

<script type="text/javascript">
$(document).ready(function () {
    var datable_datatable = $('#datatable').DataTable({
        dom: 'Brtip',
        pageLength: 100,
        language: {
            url: "{{ url('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
        },
        processing: true,
        serverSide: true,
        order: 
        [
            [ 3, 'ASC'],
        ],
        ajax: {
            url: "{{ url('produto/datatable') }}",
            data: function ( d ) {
                d.filtros = new Object;
                d.filtros.codproduto = $('#codproduto').val();
                d.filtros.barras = $('#barras').val();
                d.filtros.inativo = $('#inativo').val();
                d.filtros.produto = $('#produto').val();
                d.filtros.referencia = $('#referencia').val();
                d.filtros.preco_de = $('#preco_de').val();
                d.filtros.preco_ate = $('#preco_ate').val();
                d.filtros.codmarca = $('#codmarca').val();
                d.filtros.codsecaoproduto = $('#codsecaoproduto').val();
                d.filtros.codfamiliaproduto = $('#codfamiliaproduto').val();
                d.filtros.codgrupoproduto = $('#codgrupoproduto').val();
                d.filtros.codsubgrupoproduto = $('#codsubgrupoproduto').val();
                d.filtros.codtributacao = $('#codtributacao').val();
                d.filtros.codncm = $('#codncm').val();
                d.filtros.criacao_de = $('#criacao_de').val();
                d.filtros.criacao_ate = $('#criacao_ate').val();
                d.filtros.alteracao_de = $('#alteracao_de').val();
                d.filtros.alteracao_ate = $('#alteracao_ate').val();
                d.filtros.site = $('#site').val();
                d.filtros.inativo = $('#inativo').val();
            }
        },
        lengthChange: false,
        buttons: [
            { extend: 'copy', text: '<i class="fa fa-clipboard" aria-hidden="true"></i>', exportOptions: { columns: ':visible' } },
            { extend: 'excel', text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i>', exportOptions: { columns: ':visible' } },
            { extend: 'pdf', text: '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>', exportOptions: { columns: ':visible' } },
            { extend: 'print', text: '<i class="fa fa-print" aria-hidden="true"></i>', exportOptions: { columns: ':visible' } },
            { extend: 'colvis', text: '<i class="fa fa-columns" aria-hidden="true"></i>', exportOptions: { columns: ':visible' } },
        ],
        columnDefs: [
            {
                targets: [0, 1, 3],
                visible: false,
            },
            {
                render: function ( data, type, row ) {
                    return '<img width="100" src=' + row[3] + '>';
                },
                targets: 2
            },
            {
                render: function ( data, type, row ) {
                    var render = ''+
                        '<a href="' + row[0] + '">' + row[2] +'</a>' +
                        '<br>' +
                        '<a href="' + row[0] + '"><strong>' + data +'</strong></a>' +
                        '<br>' +
                        '<a href="secao-produto/' + row[5].secaoproduto.codsecaoproduto + '">' + row[5].secaoproduto.secaoproduto +'</a> » '+
                        '<a href="familia-produto/' + row[5].familiaproduto.codfamiliaproduto + '">' + row[5].familiaproduto.familiaproduto +'</a> » '+
                        '<a href="grupo-produto/' + row[5].grupoproduto.codgrupoproduto + '">' + row[5].grupoproduto.grupoproduto +'</a> » ' +
                        '<a href="sub-grupo-produto/' + row[5].subgrupoproduto.codsubgrupoproduto + '">' + row[5].subgrupoproduto.subgrupoproduto +'</a> » ' +
                        '<a href="marca/' + row[5].marca.codmarca + '">' + row[5].marca.marca +'</a> » ' +
                        '<span>' + row[5].referencia + '</span>';
                    return render;
                },
                targets: 4
            },
            {
                render: function ( data, type, row ) {
                    var render ='';
                    $.each(row[6], function(index, value) {
                        render += '<div class="row">';
                        render += '<div class="col-xs-4 text-right">'+ value.preco +'</div>';
                        render += '<div class="col-xs-4">'+ value.embalagem +'</div>';
                        render += '</div>';
                    });
                    
                    return render;
                },
                targets: 5
            },
            {
                render: function ( data, type, row ) {
                    var render ='';
                    $.each(row[7], function(index, value) {
                        render += '<div class="row">';
                        render += '<div class="">'+ value.variacao +'</div>';
                        //render += '<div class="col-xs-4">'+ value.embalagem +'</div>';
                        render += '</div>';
                    });
                    
                    return render;
                },
                targets: 6
            },
            { className: "text-right", "targets": 3 },
        ],
        initComplete: function(settings, json) {
            datable_datatable.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
            $('#datatable_paginate, #datatable_info').addClass('col-md-6');
            $('#datatable thead tr:first-child th:eq(2)').css({ 'min-width': "150px" });
        },
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[1] != null) {
                $(nRow).addClass('table-danger');
            }
        }
    });

    $('#codproduto').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#barras').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#inativo').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#produto').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#referencia').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#preco_de').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#preco_ate').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#codmarca').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#codsecaoproduto').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#codfamiliaproduto').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#codgrupoproduto').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#codsubgrupoproduto').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#codtributacao').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#codncm').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#criacao_de').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#criacao_ate').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#alteracao_de').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#alteracao_ate').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#site').change(function() {
        datable_datatable.ajax.reload();
    });
        $('#inativo').change(function() {
        datable_datatable.ajax.reload();
    });
    
    $('#site').select2({
        placeholder: 'Site',
        allowClear:true,
        closeOnSelect:true
    });

    $('#form-search input').blur(function(e) {
        var controlgroup = $(e.target.parentNode);
        if (!e.target.checkValidity()) {
            controlgroup.addClass('has-danger');
            e.target.reportValidity();
        } else {
            controlgroup.removeClass('has-danger');
        }
    }); 

    $("#form-search").on("change", function (e) {
        if($('#form-search')[0].checkValidity()){
            $("#form-search").submit();
        }
        return false;

    });

    var alteracao_de = $('#alteracao_de').val();
    if(alteracao_de.length > 0 ){
        $('#alteracao_ate').attr('min', alteracao_de);
    }
    $('#alteracao_de').on('change', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#alteracao_ate').empty();
            $('#alteracao_ate').attr('min', '');
        } else {
            $('#alteracao_ate').attr('min', valor);
        }

    });

    var alteracao_ate = $('#alteracao_ate').val();
    if(alteracao_ate.length > 0){
        $('#alteracao_de').attr('max', alteracao_ate);
    }
    $('#alteracao_ate').on('change', function(e) {        
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#alteracao_de').empty();
            $('#alteracao_de').attr('max', '');
        } else {
            $('#alteracao_de').attr('max', valor);
        }
    });

    var criacao_de = $('#criacao_de').val();
    if(criacao_de.length > 0 ){
        $('#criacao_ate').attr('min', criacao_de);
    }
    $('#criacao_de').on('change', function(e) {
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#criacao_ate').empty();
            $('#criacao_ate').attr('min', '');
        } else {
            $('#criacao_ate').attr('min', valor);
        }

    });

    var criacao_ate = $('#criacao_ate').val();
    if(criacao_ate.length > 0){
        $('#criacao_de').attr('max', criacao_ate);
    }
    $('#criacao_ate').on('change', function(e) {        
        e.preventDefault();
        var valor = $(this).val();
        if(valor.length === 0 ) {
            $('#criacao_de').empty();
            $('#criacao_de').attr('max', '');
        } else {
            $('#criacao_de').attr('max', valor);
        }
    });

    function setPrecoMin() {
        var valor = $('#preco_de').val();
        if(valor.length === 0 ) {
            $('#preco_ate').empty();
            $('#preco_ate').attr('min', '');
        } else {
            $('#preco_ate').attr('min', valor);
        }
    };

    function setPrecoMax() {
        var preco_de = $('#preco_de').val();
        var preco_ate = $('#preco_ate').val();
        if(preco_de.length === 0 ) {
            $('#preco_de').attr('max', preco_ate);
        }
    };

    var preco_de = $('#preco_de').val();
    if(preco_de.length > 0 ){
        $('#preco_ate').attr('min', preco_de);
    }

    var preco_ate = $('#preco_ate').val();
    if(preco_de.length > 0 ){
        $('#preco_de').attr('min', preco_ate);
    }

    $('#preco_de').on('change', function(e) {
        e.preventDefault();
        setPrecoMin();
    }).blur(function () {
        setPrecoMin();
    });

    $('#preco_ate').on('change', function(e) {
        e.preventDefault();
        setPrecoMax();
    }).blur(function () {
        setPrecoMax();
    });

});
</script>

@endsection
@stop
