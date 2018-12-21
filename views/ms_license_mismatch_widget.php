<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ms_license_mismatch-widget">
        <div id="ms_license_mismatch-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.license_mismatch">
            <h3 class="panel-title"><i class="fa fa-exclamation-circle"></i> 
                <span data-i18n="ms_office.license_mismatch"></span>
                <list-link data-url="/show/listing/ms_office/ms_office"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_license_mismatch', function( data ) {
        if(data.error){
        //alert(data.error);
        return;
        }

        var panel = $('#ms_license_mismatch-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_office';
        panel.empty();
        // Set blocks, disable if zero
        if(data.v2011 != "0"){
            panel.append(' <a href="'+baseUrl+'#2011" class="btn btn-danger"><span class="bigger-150">'+data.v2011+'</span><br>&nbsp;&nbsp;2011&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'#2011" class="btn btn-success disabled"><span class="bigger-150">'+data.v2011+'</span><br>&nbsp;&nbsp;2011&nbsp;&nbsp;</a>');
        }
        if(data.v2016 != "0"){
            panel.append(' <a href="'+baseUrl+'#2016" class="btn btn-danger"><span class="bigger-150">'+data.v2016+'</span><br>&nbsp;&nbsp;2016&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'#2016" class="btn btn-success disabled"><span class="bigger-150">'+data.v2016+'</span><br>&nbsp;&nbsp;2016&nbsp;&nbsp;</a>');
        }
        if(data.v2019 != "0"){
            panel.append(' <a href="'+baseUrl+'#2019" class="btn btn-danger"><span class="bigger-150">'+data.v2019+'</span><br>&nbsp;&nbsp;2019&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'#2019" class="btn btn-success disabled"><span class="bigger-150">'+data.v2019+'</span><br>&nbsp;&nbsp;2019&nbsp;&nbsp;</a>');
        }
    });

});

</script>