<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ms_word_generation-widget">
        <div id="ms_word_generation-widget" class="panel-heading" data-container="body" data-i18n="[title]ms_office.word_office_generation">
            <h3 class="panel-title"><i class="fa fa-file-word-o"></i> 
                <span data-i18n="ms_office.word_office_generation"></span>
                <list-link data-url="/show/listing/ms_office/ms_office"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ms_office/get_generation', function( data ) {
        if(data.error){
            //alert(data.error);
            return;
        }

        var panel = $('#ms_word_generation-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ms_office/ms_office/#';
        panel.empty();
        // Set blocks, disable if zero
        if(data.word_gen_2011 != "0"){
            panel.append(' <a href="'+baseUrl+'2011" class="btn btn-danger"><span class="bigger-150">'+data.word_gen_2011+'</span><br>&nbsp;&nbsp;2011&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'2011" class="btn btn-danger disabled"><span class="bigger-150">'+data.word_gen_2011+'</span><br>&nbsp;&nbsp;2011&nbsp;&nbsp;</a>');
        }
        if(data.word_gen_2016 != "0"){
            panel.append(' <a href="'+baseUrl+'2016" class="btn btn-warning"><span class="bigger-150">'+data.word_gen_2016+'</span><br>&nbsp;&nbsp;2016&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'2016" class="btn btn-warning disabled"><span class="bigger-150">'+data.word_gen_2016+'</span><br>&nbsp;&nbsp;2016&nbsp;&nbsp;</a>');
        }
        if(data.word_gen_2019 != "0"){
            panel.append(' <a href="'+baseUrl+'2019" class="btn btn-success"><span class="bigger-150">'+data.word_gen_2019+'</span><br>&nbsp;&nbsp;2019&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'2019" class="btn btn-success disabled"><span class="bigger-150">'+data.word_gen_2019+'</span><br>&nbsp;&nbsp;2019&nbsp;&nbsp;</a>');
        }
    });
});

</script>
