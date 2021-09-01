@extends('layouts.default')
@section('content')

<ul class="nav nav-pills m-b-10" id="myTabalt" >
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'produto'?'active':'' }}" href="{{ url('imagem?tipo=produto') }}" role="tab"  >Produtos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'marca'?'active':'' }}" href="{{ url('imagem?tipo=marca') }}" role="tab"  >Marcas</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'secao'?'active':'' }}" href="{{ url('imagem?tipo=secao') }}" role="tab"  >Seções</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'familia'?'active':'' }}" href="{{ url('imagem?tipo=familia') }}" role="tab"  >Famílias</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'grupo'?'active':'' }}" href="{{ url('imagem?tipo=grupo') }}" role="tab"  >Grupos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'subgrupo'?'active':'' }}" href="{{ url('imagem?tipo=subgrupo') }}" role="tab"  >Sub-Grupos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'outras'?'active':'' }}" href="{{ url('imagem?tipo=outras') }}" role="tab"  >Outras</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filtro['tipo'] == 'lixeira'?'active':'' }}" href="{{ url('imagem?tipo=lixeira') }}" role="tab"  >Lixeira</a>
    </li>
</ul>

@if(count($data) >0 )
<div class="row m-b-20" id="imagens">
    @foreach($data as $row)
    <div class="col-xs-6 col-md-2">
        <div class="thumb">
            <a href="{{ url('imagem',$row->codimagem) }}" class="image-popup" title="{{ $row->arquivo }}">
                <img src="{{ $row->url }}" class="thumb-img" alt="{{ $row->arquivo }}">
            </a>
            <div class="gal-detail text-xs-center">
                {{ $row->arquivo }}
                <br>
                <small class="text-muted mb-0">
                    {{ $row->observacoes }}
                </small>
            </div>
        </div>
    </div>
    @endforeach
    <div class="col-md-12">{!! $data->appends($filtro)->render() !!}</div>
</div>
@else
<div class="card">
<div class="card-block">
    <div class="card-text">Nenuhma imagem encontrada</div>
</div>
</div>
@endif

@section('buttons')

    @if($filtro['tipo'] == 'lixeira')
    <a class="btn btn-secondary btn-sm" href="{{ url("imagem/esvaziar-lixeira") }}" data-delete data-question="Tem certeza que deseja esvaziar a lixeira?" data-after="location.reload();">Esvaziar Lixeira</a>                
    @endif
    
@endsection
@section('inscript')

    <script type="text/javascript">
        $(document).ready(function () {
            /* CSS Paginação Uplon */
            $("#imagens .pagination li").addClass('page-item');
            $("#imagens .pagination li a,.pagination li span").addClass('page-link');
        });
    </script>

@endsection
@stop
