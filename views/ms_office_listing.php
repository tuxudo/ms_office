<?php $this->view('partials/head'); ?>

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
                <th data-i18n="ms_office.o365_detected_short" data-colname='ms_office.o365_detected'></th>
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
                <th data-i18n="ms_office.atp_defender_app_version" data-colname='ms_office.atp_defender_app_version'></th>
                <th data-i18n="ms_office.yammer_app_version" data-colname='ms_office.yammer_app_version'></th>
                <th data-i18n="ms_office.copilot_app_version" data-colname='ms_office.copilot_app_version'></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td data-i18n="listing.loading" colspan="32" class="dataTables_empty"></td>
              </tr>
            </tbody>
          </table>
    </div> <!-- /span -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">
    // Version: 1.0.1 - Debug search issues

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

        console.log('Initializing DataTable for MS Office listing');
        oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "ms_office.o365_detected";
                    // Do not show row if column o365_detected is empty
                    
                    // Check for column in search
                    if(d.search.value){
                        console.log('Search value:', d.search.value);
                        $.each(d.columns, function(index, item){
                            // Handle specific search components
                            if(d.search.value == 'excel_mas_yes' && item.name == 'ms_office.excel_mas'){
                                d.columns[index].search.value = '= 1';
                                console.log('Searching for excel_mas_yes, value:', d.columns[index].search.value);
                            } else if(d.search.value == 'excel_mas_no' && item.name == 'ms_office.excel_mas'){
                                d.columns[index].search.value = '= 0';
                                console.log('Searching for excel_mas_no, value:', d.columns[index].search.value);
                            } else if(d.search.value == 'word_mas_yes' && item.name == 'ms_office.word_mas'){
                                d.columns[index].search.value = '= 1';
                            } else if(d.search.value == 'word_mas_no' && item.name == 'ms_office.word_mas'){
                                d.columns[index].search.value = '= 0';
                            } else if(d.search.value == 'outlook_mas_yes' && item.name == 'ms_office.outlook_mas'){
                                d.columns[index].search.value = '= 1';
                            } else if(d.search.value == 'outlook_mas_no' && item.name == 'ms_office.outlook_mas'){
                                d.columns[index].search.value = '= 0';
                            } else if(d.search.value == 'onenote_mas_yes' && item.name == 'ms_office.onenote_mas'){
                                d.columns[index].search.value = '= 1';
                            } else if(d.search.value == 'onenote_mas_no' && item.name == 'ms_office.onenote_mas'){
                                d.columns[index].search.value = '= 0';
                            } else if(d.search.value == 'onedrive_mas_yes' && item.name == 'ms_office.onedrive_mas'){
                                d.columns[index].search.value = '= 1';
                            } else if(d.search.value == 'onedrive_mas_no' && item.name == 'ms_office.onedrive_mas'){
                                d.columns[index].search.value = '= 0';
                            } else if(d.search.value == 'remote_desktop_mas_yes' && item.name == 'ms_office.remote_desktop_mas'){
                                d.columns[index].search.value = '= 1';
                            } else if(d.search.value == 'remote_desktop_mas_no' && item.name == 'ms_office.remote_desktop_mas'){
                                d.columns[index].search.value = '= 0';
                            } else if(d.search.value == 'powerpoint_mas_yes' && item.name == 'ms_office.powerpoint_mas'){
                                d.columns[index].search.value = '= 1';
                            } else if(d.search.value == 'powerpoint_mas_no' && item.name == 'ms_office.powerpoint_mas'){
                                d.columns[index].search.value = '= 0';
                            } else if(d.search.value == 'o365_license' && item.name == 'ms_office.o365_detected'){
                                d.columns[index].search.value = '= 1';
                                console.log('Searching for o365_license, value:', d.columns[index].search.value);
                            } else if(d.search.value == 'vl_license' && item.name == 'ms_office.vl_license_type'){
                                d.columns[index].search.value = '%Volume License%';
                                console.log('Searching for vl_license, value:', d.columns[index].search.value);
                            } else if(d.search.value == 'retail_license' && item.name == 'ms_office.vl_license_type'){
                                d.columns[index].search.value = '%Home and%';
                                console.log('Searching for retail_license, value:', d.columns[index].search.value);
                            } else if(d.search.value == 'word_2011' && item.name == 'ms_office.word_office_generation'){
                                d.columns[index].search.value = '= 2011';
                            } else if(d.search.value == 'word_2016' && item.name == 'ms_office.word_office_generation'){
                                d.columns[index].search.value = '= 2016';
                            } else if(d.search.value == 'word_2019' && item.name == 'ms_office.word_office_generation'){
                                d.columns[index].search.value = '= 2019';
                            } else if(d.search.value == 'word_2021' && item.name == 'ms_office.word_office_generation'){
                                d.columns[index].search.value = '= 2021';
                            } else if(d.search.value == 'word_2024' && item.name == 'ms_office.word_office_generation'){
                                d.columns[index].search.value = '= 2024';
                            } else if(d.search.value == 'excel_2011' && item.name == 'ms_office.excel_office_generation'){
                                d.columns[index].search.value = '= 2011';
                            } else if(d.search.value == 'excel_2016' && item.name == 'ms_office.excel_office_generation'){
                                d.columns[index].search.value = '= 2016';
                            } else if(d.search.value == 'excel_2019' && item.name == 'ms_office.excel_office_generation'){
                                d.columns[index].search.value = '= 2019';
                            } else if(d.search.value == 'excel_2021' && item.name == 'ms_office.excel_office_generation'){
                                d.columns[index].search.value = '= 2021';
                            } else if(d.search.value == 'excel_2024' && item.name == 'ms_office.excel_office_generation'){
                                d.columns[index].search.value = '= 2024';
                            } else if(d.search.value == 'powerpoint_2011' && item.name == 'ms_office.powerpoint_office_generation'){
                                d.columns[index].search.value = '= 2011';
                            } else if(d.search.value == 'powerpoint_2016' && item.name == 'ms_office.powerpoint_office_generation'){
                                d.columns[index].search.value = '= 2016';
                            } else if(d.search.value == 'powerpoint_2019' && item.name == 'ms_office.powerpoint_office_generation'){
                                d.columns[index].search.value = '= 2019';
                            } else if(d.search.value == 'powerpoint_2021' && item.name == 'ms_office.powerpoint_office_generation'){
                                d.columns[index].search.value = '= 2021';
                            } else if(d.search.value == 'powerpoint_2024' && item.name == 'ms_office.powerpoint_office_generation'){
                                d.columns[index].search.value = '= 2024';
                            } else if(d.search.value == 'outlook_2011' && item.name == 'ms_office.outlook_office_generation'){
                                d.columns[index].search.value = '= 2011';
                            } else if(d.search.value == 'outlook_2016' && item.name == 'ms_office.outlook_office_generation'){
                                d.columns[index].search.value = '= 2016';
                            } else if(d.search.value == 'outlook_2019' && item.name == 'ms_office.outlook_office_generation'){
                                d.columns[index].search.value = '= 2019';
                            } else if(d.search.value == 'outlook_2021' && item.name == 'ms_office.outlook_office_generation'){
                                d.columns[index].search.value = '= 2021';
                            } else if(d.search.value == 'outlook_2024' && item.name == 'ms_office.outlook_office_generation'){
                                d.columns[index].search.value = '= 2024';
                            } else if(d.search.value == 'onenote_2011' && item.name == 'ms_office.onenote_office_generation'){
                                d.columns[index].search.value = '= 2011';
                            } else if(d.search.value == 'onenote_2016' && item.name == 'ms_office.onenote_office_generation'){
                                d.columns[index].search.value = '= 2016';
                            } else if(d.search.value == 'onenote_2019' && item.name == 'ms_office.onenote_office_generation'){
                                d.columns[index].search.value = '= 2019';
                            } else if(d.search.value == 'onenote_2021' && item.name == 'ms_office.onenote_office_generation'){
                                d.columns[index].search.value = '= 2021';
                            } else if(d.search.value == 'onenote_2024' && item.name == 'ms_office.onenote_office_generation'){
                                d.columns[index].search.value = '= 2024';
                            } else if(item.name == 'ms_office.' + d.search.value){
                                d.columns[index].search.value = '> 0';
                            }
                        });
                    }
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

                // Format o365 detected
                var status=$('td:eq(3)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(3)', nRow).html(status)

                // Format shared o365
                var status=$('td:eq(5)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(5)', nRow).html(status)

                // Format Excel MAS
                var status=$('td:eq(8)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(8)', nRow).html(status)

                // Format OneNote MAS
                var status=$('td:eq(11)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(11)', nRow).html(status)

                // Format Outlook MAS
                var status=$('td:eq(14)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(14)', nRow).html(status)

                // Format PowerPoint MAS
                var status=$('td:eq(17)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(17)', nRow).html(status)

                // Format Word MAS
                var status=$('td:eq(20)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(20)', nRow).html(status)

                // Format OneDrive MAS
                var status=$('td:eq(23)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(23)', nRow).html(status)

                // Format Remote Desktop MAS
                var status=$('td:eq(25)', nRow).html();
                status = status == 1 ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' :
                (status == 0 && status != '' ? '<span class="label label-success">'+i18n.t('no')+'</span>' : '')
                $('td:eq(25)', nRow).html(status)
            }
        });

        // Use hash as search query
        if(window.location.hash.substring(1))
        {
            oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
        }
    });
</script>

<?php $this->view('partials/foot')?>
