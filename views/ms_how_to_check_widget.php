<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ms_how_to_check-widget">
        <div id="ms_how_to_check-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.how_to_check_widget.title">
            <h3 class="panel-title"><i class="fa fa-check-circle "></i> 
                <span data-i18n="ms_office.how_to_check_widget.title"></span>
                <list-link data-url="/show/listing/ms_office/ms_mau"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_how_to_check', function( data ) {
        if(data.error){
        //alert(data.error);
        return;
        }

        var panel = $('#ms_how_to_check-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_mau/#';
        panel.empty();
        // Set blocks
        panel.append(' <a href="'+baseUrl+'Manual" class="btn btn-success"><span class="bigger-150">'+data.Manual+'</span><br>'+i18n.t('ms_office.how_to_check_widget.manual')+'</a>');
        panel.append(' <a href="'+baseUrl+'AutomaticCheck" class="btn btn-warning"><span class="bigger-150">'+data.AutomaticCheck+'</span><br>'+i18n.t('ms_office.how_to_check_widget.automatic_check')+'</a>');
        panel.append(' <a href="'+baseUrl+'AutomaticDownload" class="btn btn-danger"><span class="bigger-150">'+data.AutomaticDownload+'</span><br>'+i18n.t('ms_office.how_to_check_widget.automatic_download')+'</a>');
    });

});

</script>
