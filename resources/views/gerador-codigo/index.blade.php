@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-md-2">
    <div class="card ">
      <h4 class="card-header">Tabelas</h4>
      <div class="card-block">
        <ul class="nav nav-pills nav-stacked m-b-10" id="myTabalt" role="tablist">
          @foreach ($tabelas as $tabela)
            <li class="nav-item">
              <a class="nav-link link-tabela"  data-tabela="{{ $tabela->table_name }}" href="#">{{ $tabela->table_name }}</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <div class='col-md-10' style='display: none' id='gerador'>
  </div>
</div>
@section('buttons')
    
@endsection
@section('inactive')
    
@endsection
@section('creation')
    
@endsection
@section('inscript')
<script type="text/javascript">
    
    function abreGerador(tabela) {
        
        $.get('{{ url('gerador-codigo') }}/' + tabela).done(function(data) {
            $('#gerador').html(data); 
        });
        
        $('#gerador').fadeIn();
        
    }
    var tabela = null;
    $(document).ready(function () {
        $('.link-tabela').click(function(e) {
            $('.link-tabela').removeClass('active');
            $(this).addClass('active');
            tabela = $(this).data('tabela');
            abreGerador(tabela);
        });
    });
</script>
@endsection
@stop