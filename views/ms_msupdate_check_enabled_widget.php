<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="msupdate_check_enabled-widget">
        <div id="msupdate_check_enabled-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.msupdate_check_enabled">
            <h3 class="panel-title"><i class="fa fa-bullseye"></i> 
                <span data-i18n="ms_office.msupdate_check_enabled"></span>
                <list-link data-url="/show/listing/ms_office/ms_mau"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_msupdate_check_enabled', function( data ) {
        if(data.error){
            //alert(data.error);
            return;
        }

        var panel = $('#msupdate_check_enabled-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_mau/';
        panel.empty();
        // Set blocks, disable if zero
        if(data.enabled != "0"){
            panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.enabled+'</span><br>'+i18n.t('enabled')+'</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-danger disabled"><span class="bigger-150">'+data.enabled+'</span><br>'+i18n.t('enabled')+'</a>');
        }
        if(data.disabled != "0"){
            panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.disabled+'</span><br>'+i18n.t('disabled')+'</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-success disabled"><span class="bigger-150">'+data.disabled+'</span><br>'+i18n.t('disabled')+'</a>');
        }
    });

});

</script>
