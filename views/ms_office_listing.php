<?php $this->view('partials/head'); ?>

<?php
//Initialize models needed for the table
new Machine_model;
new Reportdata_model;
new Ms_office_model;
?>

<div class="container">
  <div class="row">
  	<div class="col-lg-12">
	<h3><span data-i18n="ms_office.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
		        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
		        <th data-i18n="ms_office.vl_license_type" data-colname='ms_office.vl_license_type'></th>
		        <th data-i18n="ms_office.o365_license_count_short" data-colname='ms_office.o365_license_count'></th>
		        <th data-i18n="ms_office.shared_o365_license_short" data-colname='ms_office.shared_o365_license'></th>
		        <th data-i18n="ms_office.excel_office_generation" data-colname='ms_office.excel_office_generation'></th>
		        <th data-i18n="ms_office.excel_app_version" data-colname='ms_office.excel_app_version'></th>
		        <th data-i18n="ms_office.excel_mas_short" data-colname='ms_office.excel_mas'></th>
		        <th data-i18n="ms_office.onenote_office_generation" data-colname='ms_office.onenote_office_generation'></th>
		        <th data-i18n="ms_office.onenote_app_version" data-colname='ms_office.onenote_app_version'></th>
		        <th data-i18n="ms_office.onenote_mas_short" data-colname='ms_office.onenote_mas'></th>
		        <th data-i18n="ms_office.outlook_office_generation" data-colname='ms_office.outlook_office_generation'></th>
		        <th data-i18n="ms_office.outlook_app_version" data-colname='ms_office.outlook_app_version'></th>
		        <th data-i18n="ms_office.outlook_mas_short" data-colname='ms_office.outlook_mas'></th>
		        <th data-i18n="ms_office.powerpoint_office_generation" data-colname='ms_office.powerpoint_office_generation'></th>
		        <th data-i18n="ms_office.powerpoint_app_version" data-colname='ms_office.powerpoint_app_version'></th>
		        <th data-i18n="ms_office.powerpoint_mas_short" data-colname='ms_office.powerpoint_mas'></th>
		        <th data-i18n="ms_office.word_office_generation" data-colname='ms_office.word_office_generation'></th>
		        <th data-i18n="ms_office.word_app_version" data-colname='ms_office.word_app_version'></th>
		        <th data-i18n="ms_office.word_mas_short" data-colname='ms_office.word_mas'></th>
		        <th data-i18n="ms_office.skype_for_business_app_version" data-colname='ms_office.skype_for_business_app_version'></th>
		        <th data-i18n="ms_office.onedrive_app_version" data-colname='ms_office.onedrive_app_version'></th>
		        <th data-i18n="ms_office.onedrive_mas_short" data-colname='ms_office.onedrive_mas'></th>
		        <th data-i18n="ms_office.remote_desktop_app_version" data-colname='ms_office.remote_desktop_app_version'></th>
		        <th data-i18n="ms_office.remote_desktop_mas_short" data-colname='ms_office.remote_desktop_mas'></th>
		        <th data-i18n="ms_office.edge_app_version" data-colname='ms_office.edge_app_version'></th>
		        <th data-i18n="ms_office.teams_app_version" data-colname='ms_office.teams_app_version'></th>
		        <th data-i18n="ms_office.company_portal_app_version" data-colname='ms_office.company_portal_app_version'></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
		    	     <td data-i18n="listing.loading" colspan="28" class="dataTables_empty"></td>
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

            columnDefs.push({name: $(this).data('colname'), targets: col});

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
                    d.mrColNotEmpty = "ms_office.o365_detected";
                    // Do not show row if column o365_detected is empty
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
	        	var link = mr.getClientDetailLink(name, sn, '#tab_ms_office-tab');
	        	$('td:eq(0)', nRow).html(link);

	        	// Format shared o365
	        	var status=$('td:eq(4)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(4)', nRow).html(status)
	        	
	        	// Format Excel MAS
	        	var status=$('td:eq(7)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(7)', nRow).html(status)
	        	
	        	// Format OneNote MAS
	        	var status=$('td:eq(10)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(10)', nRow).html(status)
	        	
	        	// Format Outlook MAS
	        	var status=$('td:eq(13)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(13)', nRow).html(status)
	        	
	        	// Format PowerPoint MAS
	        	var status=$('td:eq(16)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(16)', nRow).html(status)
	        	
	        	// Format Word MAS
	        	var status=$('td:eq(19)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(19)', nRow).html(status)
	        	
	        	// Format OneDrive MAS
	        	var status=$('td:eq(22)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(22)', nRow).html(status)
	        	
	        	// Format Remote Desktop MAS
	        	var status=$('td:eq(24)', nRow).html();
	        	status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
	        	(status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
	        	$('td:eq(24)', nRow).html(status)
	        	
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
