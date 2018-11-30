<div class="col-lg-4 col-md-6">

    <div class="panel panel-default" id="ms_mau_channel-widget">

        <div id="ms_mau_channel-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.mau_channel_widget.title">

            <h3 class="panel-title"><i class="fa fa-book"></i> 
                <span data-i18n="ms_office.mau_channel_widget.title"></span>
                <list-link data-url="/show/listing/ms_office/ms_mau"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>


    </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_channel', function( data ) {
        if(data.error){
        //alert(data.error);
        return;
        }

        var panel = $('#ms_mau_channel-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_mau/#';
        panel.empty();
        // Set statuses
        panel.append(' <a href="'+baseUrl+'Production" class="btn btn-success"><span class="bigger-150">'+data.Production+'</span><br>'+i18n.t('ms_office.mau_channel_widget.production')+'</a>');
        panel.append(' <a href="'+baseUrl+'InsiderSlow" class="btn btn-warning"><span class="bigger-150">'+data.InsiderSlow+'</span><br>'+i18n.t('ms_office.mau_channel_widget.insider_slow')+'</a>');
        panel.append(' <a href="'+baseUrl+'InsiderFast" class="btn btn-danger"><span class="bigger-150">'+data.InsiderFast+'</span><br>'+i18n.t('ms_office.mau_channel_widget.insider_fast')+'</a>');
    });

});

</script>
