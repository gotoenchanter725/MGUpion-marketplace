<div id="div-imagens">
    <div id="carouselImagens" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
        @for ($i = 0; $i < sizeof($imagens); $i++)
            <li data-target="#carouselImagens" data-slide-to="{{ $i }}" class="{{ ($i == 0)?'active':'' }}"></li>
        @endfor            
        </ol>    
        <div class="carousel-inner" role="listbox">
        @if (sizeof($imagens) == 0)
            <div class="carousel-item text-center {{ ($i == 0)?'active':'' }}">
                <img class="d-block img-fluid" src="{{ URL::asset('public/imagens/semimagem.jpg') }}" style='margin: 0 auto;'>
            </div>
        @endif        
        @foreach($imagens as $i => $imagem)
            <div class="carousel-item {{ ($i == 0)?'active':'' }}">
                <a href="{{ url("imagem/$imagem->codimagem") }}">
                    <img class="d-block img-fluid" src="{{ URL::asset('public/imagens/'.$imagem->arquivo) }}" >
                </a>
                <div class="carousel-caption">
                    <p>{{ $imagem->arquivo }}</p>
                    <p>
                        <a class="btn btn-secondary btn-sm waves-effect" data-toggle="tooltip" title="Editar" href="{{ url("/imagem/$imagem->codimagem/edit") }}"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-secondary btn-sm waves-effect" data-toggle="tooltip" title="Excluir Imagem" href="{{ url("imagem/$imagem->codimagem/inactivate") }}" data-activate data-question="Tem certeza que deseja excluir esta imagem?" data-after="location.reload()"><i class="fa fa-trash"></i></a>
                    </p>
                </div>                
            </div>
        @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselImagens" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselImagens" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>   
</div>