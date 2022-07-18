<?php $this->view('partials/head'); ?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
	<h3><span data-i18n="ms_office.mau_report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="ms_office.lastcheckforupdates" data-colname='ms_office.lastcheckforupdates'></th>
		        <th data-i18n="ms_office.howtocheck" data-colname='ms_office.howtocheck'></th>
		        <th data-i18n="ms_office.enablecheckforupdatesbutton" data-colname='ms_office.enablecheckforupdatesbutton'></th>
		        <th data-i18n="ms_office.disableinsidercheckbox" data-colname='ms_office.disableinsidercheckbox'></th>
		        <th data-i18n="ms_office.channelname" data-colname='ms_office.channelname'></th>
		        <th data-i18n="ms_office.manifestserver" data-colname='ms_office.manifestserver'></th>
		        <th data-i18n="ms_office.updatecache" data-colname='ms_office.updatecache'></th>
		        <th data-i18n="ms_office.autoupdate_app_version_short" data-colname='ms_office.autoupdate_app_version'></th>
		        <th data-i18n="ms_office.mau_privilegedhelpertool_short" data-colname='ms_office.mau_privilegedhelpertool'></th>
		        <th data-i18n="ms_office.sendalltelemetryenabled_short" data-colname='ms_office.sendalltelemetryenabled'></th>
		        <th data-i18n="ms_office.startdaemononapplaunch_short" data-colname='ms_office.startdaemononapplaunch'></th>
		        <th data-i18n="ms_office.msupdate_check_enabled_short" data-colname='ms_office.msupdate_check_enabled'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
		    	     <td data-i18n="listing.loading" colspan="14" class="dataTables_empty"></td>
		    	</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 13 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

	$(document).on('appUpdate', function(e){

		var oTable = $('.table').DataTable();
		oTable.ajax.reload();
		return;

	});

	$(document).on('appReady', function(e, lang) {

        // Get modifiers from data attribute
        var mySort = [], // Initial sort
            hideThese = [], // Hidden columns
            col = 0, // Column counter
            runtypes = [], // Array for runtype column
            columnDefs = [{ visible: false, targets: hideThese }]; // Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col, render: $.fn.dataTable.render.text()});

            if($(this).data('sort')){
                mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide')){
                hideThese.push(col);
            }

            col++
        });

	    oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "ms_office.enablecheckforupdatesbutton";
                    // Do not show row if column enablecheckforupdatesbutton is empty
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
		    createdRow: function( nRow, aData, iDataIndex ) {
	        	// Update name in first column to link
	        	var name=$('td:eq(0)', nRow).html();
	        	if(name == ''){name = "No Name"};
	        	var sn=$('td:eq(1)', nRow).html();
	        	var link = mr.getClientDetailLink(name, sn, '#tab_ms_office');
	        	$('td:eq(0)', nRow).html(link);
	        	
	        	// Format time, if timestamp
	        	var updatecheck = $('td:eq(2)', nRow).html(); 
	        	if (! isNaN(updatecheck) && updatecheck !== ""){
	        	    parseInt(updatecheck);
	        	    var date = new Date(updatecheck * 1000);
	        	    $('td:eq(2)', nRow).html('<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>');
	        	}

	        	// Format update button
	        	var status=$('td:eq(4)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
	        	$('td:eq(4)', nRow).html(status)
	        	
	        	// Format insider box
	        	var status=$('td:eq(5)', nRow).html();
	        	status = status == 1 ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' : '')
	        	$('td:eq(5)', nRow).html(status)
	        	
	        	// Format helper tool
	        	var status=$('td:eq(10)', nRow).html();
	        	status = status == 1 ? '<span class="label label-success">'+i18n.t('ms_office.installed')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-danger">'+i18n.t('ms_office.not_installed')+'</span>' : '')
	        	$('td:eq(10)', nRow).html(status)
	        	
	        	// Format telemetry
	        	var status=$('td:eq(11)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
	        	$('td:eq(11)', nRow).html(status)
	        	
	        	// Format daemon start
	        	var status=$('td:eq(12)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
	        	$('td:eq(12)', nRow).html(status)
	        	
	        	// Format msupdate_check_enabled
	        	var status=$('td:eq(13)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' : '')
	        	$('td:eq(13)', nRow).html(status)
	        	
		    }
	    } );
        
	    // Use hash as search query
	    if(window.location.hash.substring(1))
	    {
		    oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
	    }

	} );
</script>

<?php $this->view('partials/foot')?>
