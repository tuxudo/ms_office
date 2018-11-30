<div class="col-lg-4 col-md-6">

    <div class="panel panel-default" id="ms_version-widget">

        <div id="ms_version-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.version_widget.title">

            <h3 class="panel-title"><i class="fa fa-book"></i> 
                <span data-i18n="ms_office.version_widget.title"></span>
                <list-link data-url="/show/listing/ms_office/ms_version"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>


    </div><!-- /panel -->

</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_license', function( data ) {
        if(data.error){
        //alert(data.error);
        return;
        }

        var panel = $('#ms_version-widget div.panel-body'),
        baseUrl = appUrl + 'show/listing/ms_office/ms_version/#';
        panel.empty();
        // Set statuses
        panel.append(' <a href="'+baseUrl+'o365" class="btn btn-success"><span class="bigger-150">'+data.o365+'</span><br>'+i18n.t('ms_office.version_widget.o365')+'</a>');
        panel.append(' <a href="'+baseUrl+'vl" class="btn btn-success"><span class="bigger-150">'+data.vl+'</span><br>'+i18n.t('ms_office.version_widget.vl')+'</a>');
        panel.append(' <a href="'+baseUrl+'retail" class="btn btn-success"><span class="bigger-150">'+data.retail+'</span><br>'+i18n.t('ms_office.version_widget.retail')+'</a>');
    });

});

</script>
