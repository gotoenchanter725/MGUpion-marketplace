<!-- App js -->
<script src="{{ URL::asset('public/assets/js/jquery.core.js') }}"></script>
<script src="{{ URL::asset('public/assets/js/jquery.app.js') }}"></script>
<script type="text/javascript">
    var baseUrl = '{{ url('/') }}';    
    $(document).ready(function() {
        //console.log(window.location.href);
        $("#sidebar-menu a").each(function() {
            //console.log(this.href);
            //console.log(window.location.href.indexOf(this.href));
            if (window.location.href.indexOf(this.href) != -1) {
                $(this).addClass("active");
                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().prev().addClass("active"); // add active class to an anchor
                $(this).parent().parent().prev().click(); // click the item to make it drop
            }
        });
        $('.toggle-fullscreen').click(function() {
	    $(document).toggleFullScreen();
        });
    });
</script>
@include('errors.flash')
</body>
</html>
