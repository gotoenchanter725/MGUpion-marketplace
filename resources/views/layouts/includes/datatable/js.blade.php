<?php
$page_length = $page_length??100;
$link_column = $link_column??2;
?>
<script type="text/javascript">
$(document).ready(function () {

    var datable_{{ $id }} = $('#{{ $id }}').DataTable({
        dom: 'Brtip',
        pageLength: {{ $page_length }},
        language: {
            url: "{{ URL::asset('public/assets/plugins/datatables/Portuguese-Brasil.lang') }}"
        },
        processing: true,
        serverSide: true,
        order: 
        [
        @foreach ($order as $o)
            [ {{ $o['column'] }}, '{{ $o['dir'] }}'],
        @endforeach
        ],
        ajax: {
            url: '{{ $url }}',
            data: function ( d ) {
                d.filtros = new Object;
                @foreach ($filtros as $campo => $filtro)
                    d.filtros.{{ $filtro }} = $('#{{ (is_numeric($campo)?$filtro:$campo) }}').val();
                @endforeach
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
            @if(isset($estilos))
                @foreach($estilos as $estilo)
                    {!! json_encode($estilo) !!},
                @endforeach
            @endif
            {
                targets: [0, 1],
                visible: false,
            },
            {
                render: function ( data, type, row ) {
                    return '<a href="' + row[0] + '">' + data +'</a>';
                },
                targets: {{ $link_column }}
            }
        ],
        initComplete: function(settings, json) {
            datable_{{ $id }}.buttons().container().appendTo('#{{ $id }}_wrapper .col-md-6:eq(0)');
            $('#{{ $id }}_paginate, #{{ $id }}_info').addClass('col-md-6');
        },
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[1] != null) {
                $(nRow).addClass('table-danger');
            }
        }
    });

    @foreach ($filtros as $campo => $filtro)
        $('#{{ (is_numeric($campo)?$filtro:$campo) }}').change(function() {
            datable_{{ $id }}.ajax.reload();
        });
    @endforeach

});

</script>
