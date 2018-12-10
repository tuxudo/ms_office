<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ms_license_type-widget">
        <div id="ms_license_type-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.license_type_widget.title">
            <h3 class="panel-title"><i class="fa fa-id-card"></i> 
                <span data-i18n="ms_office.license_type_widget.title"></span>
                <list-link data-url="/show/listing/ms_office/ms_office"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_license_type', function( data ) {
        if(data.error){
        //alert(data.error);
        return;
        }

        var panel = $('#ms_license_type-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_office';
        panel.empty();
        // Set blocks, disable if zero
        if(data.o365 != "0"){
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+data.o365+'</span><br>'+i18n.t('ms_office.license_type_widget.o365')+'</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+data.o365+'</span><br>'+i18n.t('ms_office.license_type_widget.o365')+'</a>');
        }
        if(data.vl != "0"){
            panel.append(' <a href="'+baseUrl+'#Volume" class="btn btn-info"><span class="bigger-150">'+data.vl+'</span><br>'+i18n.t('ms_office.license_type_widget.vl')+'</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'#Volume" class="btn btn-info disabled"><span class="bigger-150">'+data.vl+'</span><br>'+i18n.t('ms_office.license_type_widget.vl')+'</a>');
        }
        if(data.retail != "0"){
            panel.append(' <a href="'+baseUrl+'#Retail" class="btn btn-info"><span class="bigger-150">'+data.retail+'</span><br>'+i18n.t('ms_office.license_type_widget.retail')+'</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'#Retail" class="btn btn-info disabled"><span class="bigger-150">'+data.retail+'</span><br>'+i18n.t('ms_office.license_type_widget.retail')+'</a>');
        }
    });

});

</script>