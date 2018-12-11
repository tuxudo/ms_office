<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ms_remote_desktop_mas-widget">
        <div id="ms_remote_desktop_mas-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.remote_desktop_mas">
            <h3 class="panel-title"><i class="fa fa-desktop"></i> 
                <span data-i18n="ms_office.remote_desktop_mas"></span>
                <list-link data-url="/show/listing/ms_office/ms_office"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_mas', function( data ) {
        if(data.error){
            //alert(data.error);
            return;
        }

        var panel = $('#ms_remote_desktop_mas-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_office/';
        panel.empty();
        // Set blocks, disable if zero
        if(data.remote_desktop_mas_yes != "0"){
            panel.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+data.remote_desktop_mas_yes+'</span><br>&nbsp;&nbsp;'+i18n.t('yes')+'&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-warning disabled"><span class="bigger-150">'+data.remote_desktop_mas_yes+'</span><br>&nbsp;&nbsp;'+i18n.t('yes')+'&nbsp;&nbsp;</a>');
        }
        if(data.remote_desktop_mas_no != "0"){
            panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.remote_desktop_mas_no+'</span><br>&nbsp;&nbsp;'+i18n.t('no')+'&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-success disabled"><span class="bigger-150">'+data.remote_desktop_mas_no+'</span><br>&nbsp;&nbsp;'+i18n.t('no')+'&nbsp;&nbsp;</a>');
        }
    });
});

</script>
