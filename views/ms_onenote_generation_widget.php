<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ms_onenote_generation-widget">
        <div id="ms_onenote_generation-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.onenote_office_generation">
            <h3 class="panel-title"><i class="fa fa-sticky-note"></i> 
                <span data-i18n="ms_office.onenote_office_generation"></span>
                <list-link data-url="/show/listing/ms_office/ms_office"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_onenote_generation', function( data ) {
        if(data.error){
            //alert(data.error);
            return;
        }

        var panel = $('#ms_onenote_generation-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_office/#';
        panel.empty();
        // Set statuses
        panel.append(' <a href="'+baseUrl+'2019" class="btn btn-success"><span class="bigger-150">'+data.gen_2019+'</span><br>&nbsp;&nbsp;2019&nbsp;&nbsp;</a>');
        panel.append(' <a href="'+baseUrl+'2016" class="btn btn-warning"><span class="bigger-150">'+data.gen_2016+'</span><br>&nbsp;&nbsp;2016&nbsp;&nbsp;</a>');
    });

});

</script>
