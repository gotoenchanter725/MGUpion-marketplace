<table id="{{ $id }}" class="display table table-hover table-striped table-sm" cellspacing="0" width="100%">
    <thead>
        <tr>
          @foreach ($colunas as $coluna)
            <th>{{$coluna}}</th>
          @endforeach
        </tr>
    </thead>
</table>
