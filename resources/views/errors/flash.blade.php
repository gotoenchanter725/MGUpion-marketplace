<script type="text/javascript">
    $(document).ready(function() {
        toastr.options = {
          "positionClass": "toast-bottom-right"
        };    
        
        @if (Session::has('flash_update'))
            toastr.success('{{ Session::get('flash_update') }}');
        @endif

        @if (Session::has('flash_create'))
            toastr.success('{{ Session::get('flash_create') }}');
        @endif

        @if (Session::has('flash_delete'))
            toastr.success('{{ Session::get('flash_delete') }}');
        @endif
        
    });
</script>

