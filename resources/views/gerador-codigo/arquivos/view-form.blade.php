@foreach ($cols['colunas'] as $nome => $col)
<fieldset class="form-group">
    {!! '{!!' !!} Form::label('{{ $nome }}', '{{ ucfirst($nome) }}') !!}
@if ($col['type'] == 'number')
    {!! '{!!' !!} Form::{{ $col['type'] }}('{{ $nome }}', null, ['class'=> 'form-control', 'id'=>'{{ $nome }}', 'step'=>'{{ $col['step'] }}', 'min'=>'{{ $col['step'] }}'{!! ($col['required'])?', \'required\'=>\'required\'':'' !!}@if($loop->first){!! ', \'autofocus\'' !!}@endif]) !!}
@elseif ($col['type'] == 'text')
    {!! '{!!' !!} Form::{{ $col['type'] }}('{{ $nome }}', null, ['class'=> 'form-control', 'id'=>'{{ $nome }}', 'maxlength'=>'{{ $col['maxlength'] }}'{!! ($col['required'])?', \'required\'=>\'required\'':'' !!}@if($loop->first){!! ', \'autofocus\'' !!}@endif]) !!}
@else
    {!! '{!!' !!} Form::{{ $col['type'] }}('{{ $nome }}', null, ['class'=> 'form-control', 'id'=>'{{ $nome }}'{!! ($col['required'])?', \'required\'=>\'required\'':'' !!}@if($loop->first){!! ', \'autofocus\'' !!}@endif]) !!}
@endif
</fieldset>
@endforeach
<fieldset class="form-group">
   {!! '{!!' !!} Form::submit('Salvar', array('class' => 'btn btn-primary')) !!}
</fieldset>

@@section('inscript')
<script src="{{ '{{' }} URL::asset('public/assets/js/setcase.js') }}"></script>
<script src="{{ '{{' }} URL::asset('public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
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
@foreach ($cols['setcase'] as $col)
    $("#{{ $col }}").Setcase();
    $("#{{ $col }}").maxlength({alwaysShow: true});
@endforeach
});
</script>
@@endsection