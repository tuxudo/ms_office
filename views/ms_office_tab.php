<div id="ms_office-tab"></div>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/ms_office/get_tab_data/' + serialNumber, function(data){
        var skipThese = ['id','serial_number'];
        $.each(data, function(i,d){

            // Generate rows from data
            var rows = ''
            var rows_mau = ''
            var rows_excel = ''
            var rows_word = ''
            var rows_ppt = ''
            var rows_outlook = ''
            var rows_onenote = ''
            var rows_onedrive = ''
            var rows_teams = ''
            var rows_reportdestkop = ''
            var rows_sfb = ''
            var rows_edge = ''
            var rows_company_portal = ''
            var rows_defender = ''
            var rows_yammer = ''
            var rows_reg_apps = '<tr><td>'+i18n.t('ms_office.no_registeredapplications')+'</td><td></td><td></td><td></td><td></td><td></td></tr>'
            for (var prop in d){
                // Skip skipThese
                if(skipThese.indexOf(prop) == -1){
                    // Do nothing for empty values to blank them
                    if (d[prop] == '' || d[prop] == null){
                        rows = rows

                    // Format enabled/disabled
                    } else if((prop == 'enablecheckforupdatesbutton' || prop == 'sendalltelemetryenabled') && d[prop] == 0){
                        rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('disabled')+'</td></tr>';
                    } else if((prop == 'enablecheckforupdatesbutton' || prop == 'sendalltelemetryenabled') && d[prop] == 1){
                        rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('enabled')+'</td></tr>';

                    // Format enabled/disabled insider checkbox
                    } else if(( prop == 'disableinsidercheckbox' ) && d[prop] == 1){
                        rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('disabled')+'</td></tr>';
                    } else if(( prop == 'disableinsidercheckbox' ) && d[prop] == 0){
                        rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('enabled')+'</td></tr>';

                    // Format helper tool
                    } else if((prop == 'mau_privilegedhelpertool' ) && d[prop] == 0){
                        rrows_mauows = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('ms_office.not_installed')+'</td></tr>';
                    } else if((prop == 'mau_privilegedhelpertool' ) && d[prop] == 1){
                        rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('ms_office.installed')+'</td></tr>';

                    // Format yes/no
                    } else if((prop == "shared_o365_license" || prop == "o365_detected") && d[prop] == 0){
                        rows = rows + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "shared_o365_license" || prop == "o365_detected") && d[prop] == 1){
                        rows = rows + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_mau
                    } else if((prop == "startdaemononapplaunch" || prop == "autoupdate_mas" || prop == "msupdate_check_enabled") && d[prop] == 0){
                        rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "startdaemononapplaunch" || prop == "autoupdate_mas" || prop == "msupdate_check_enabled") && d[prop] == 1){
                        rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_excel
                    } else if((prop == "excel_mas") && d[prop] == 0){
                        rows_excel = rows_excel + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "excel_mas") && d[prop] == 1){
                        rows_excel = rows_excel + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_word
                    } else if((prop == "word_mas") && d[prop] == 0){
                        rows_word = rows_word + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "word_mas") && d[prop] == 1){
                        rows_word = rows_word + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_ppt
                    } else if((prop == "powerpoint_mas") && d[prop] == 0){
                        rows_ppt = rows_ppt + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "powerpoint_mas") && d[prop] == 1){
                        rows_ppt = rows_ppt + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_outlook
                    } else if((prop == "outlook_mas") && d[prop] == 0){
                        rows_outlook = rows_outlook + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "outlook_mas") && d[prop] == 1){
                        rows_outlook = rows_outlook + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_onenote
                    } else if((prop == "onenote_mas") && d[prop] == 0){
                        rows_onenote = rows_onenote + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "onenote_mas") && d[prop] == 1){
                        rows_onenote = rows_onenote + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_onedrive
                    } else if((prop == "onedrive_mas") && d[prop] == 0){
                        rows_onedrive = rows_onedrive + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "onedrive_mas") && d[prop] == 1){
                        rows_onedrive = rows_onedrive + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format yes/no rows_reportdestkop
                    } else if((prop == "remote_desktop_mas") && d[prop] == 0){
                        rows_reportdestkop = rows_reportdestkop + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "remote_desktop_mas") && d[prop] == 1){
                        rows_reportdestkop = rows_reportdestkop + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';

                    // Format yes/no rows_sfb
                    } else if((prop == "skype_for_business_mas") && d[prop] == 0){
                        rows_sfb = rows_sfb + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "skype_for_business_mas") && d[prop] == 1){
                        rows_sfb = rows_sfb + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';

                    // Format yes/no rows_teams
                    } else if((prop == "teams_mas") && d[prop] == 0){
                        rows_teams = rows_teams + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                    } else if((prop == "teams_mas") && d[prop] == 1){
                        rows_teams = rows_teams + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                        
                    // Else if, AutoUpdate
                    } else if(prop.indexOf('autoupdate_') > -1 || prop == 'channelname'  || prop == 'howtocheck'  || prop == 'lastcheckforupdates'  || prop == 'manifestserver'  || prop == 'updatecache' ){
                        // Format last check date, if timestamp
                        if (prop == 'lastcheckforupdates' && ! isNaN(d[prop]) && d[prop] !== ""){
                            var date = new Date(d[prop] * 1000);
                            rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td><span title="'+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span></td></tr>';
                        } else {
                            rows_mau = rows_mau + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        }
                        
                    // Else if, Excel
                    } else if(prop.indexOf('excel_') > -1){
                        rows_excel = rows_excel + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Outlook
                    } else if(prop.indexOf('outlook_') > -1){
                        rows_outlook = rows_outlook + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, OneNote
                    } else if(prop.indexOf('onenote_') > -1){
                        rows_onenote = rows_onenote + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, OneDrive
                    } else if(prop.indexOf('onedrive_') > -1){
                        rows_onedrive = rows_onedrive + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, PowerPoint
                    } else if(prop.indexOf('powerpoint_') > -1){
                        rows_ppt = rows_ppt + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Remote Desktop
                    } else if(prop.indexOf('remote_desktop_') > -1){
                        rows_reportdestkop = rows_reportdestkop + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Skype for Business
                    } else if(prop.indexOf('skype_for_business_') > -1){
                        rows_sfb = rows_sfb + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Teams
                    } else if(prop.indexOf('teams_') > -1){
                        rows_teams= rows_teams + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Word
                    } else if(prop.indexOf('word_') > -1){
                        rows_word = rows_word + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Company Portal
                    } else if(prop.indexOf('company_portal_') > -1){
                        rows_company_portal = rows_company_portal + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Edge
                    } else if(prop.indexOf('edge_') > -1){
                        rows_edge = rows_edge + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Dedender
                    } else if(prop.indexOf('atp_defender_') > -1){
                        rows_defender = rows_defender + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    // Else if, Yammer
                    } else if(prop.indexOf('yammer_app_') > -1){
                        rows_yammer = rows_yammer + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    
                    // Else if build out the registered applications table
                    } else if(prop == "registeredapplications"){
                        var reg_apps_data = JSON.parse(d['registeredapplications']);
                        rows_reg_apps = '<tr><th>'+i18n.t('ms_office.application')+'</th><th>'+i18n.t('ms_office.application_id')+'</th><th>'+i18n.t('ms_office.title')+'</th><th>'+i18n.t('ms_office.versionondisk')+'</th><th>'+i18n.t('ms_office.baseline_version')+'</th><th>'+i18n.t('ms_office.update_version')+'</th><th>'+i18n.t('ms_office.date')+'</th></tr>'
                        $.each(reg_apps_data, function(i,d){
                            if (typeof d['application_id'] !== "undefined"){var application_id = d['application_id']}else{var application_id = ""}
                            if (typeof d['title'] !== "undefined"){var title = d['title']}else{var title = ""}
                            if (typeof d['versionondisk'] !== "undefined"){var versionondisk = d['versionondisk']}else{var versionondisk = ""}
                            if (typeof d['baseline_version'] !== "undefined"){var baseline_version = d['baseline_version']}else{var baseline_version = ""}
                            if (typeof d['update_version'] !== "undefined"){var update_version = d['update_version']}else{var update_version = ""}
                            if (typeof d['date'] !== "undefined"){var date = d['date']}else{var date = ""}
                            // Generate rows from data
                            rows_reg_apps = rows_reg_apps + '<tr><td>'+i+'</td><td>'+application_id+'</td><td>'+title+'</td><td>'+versionondisk+'</td><td>'+baseline_version+'</td><td>'+update_version+'</td><td>'+date+'</td></tr>';
                        })
                        rows_reg_apps = rows_reg_apps // Close registered applications table framework

                    // Else, build out rows
                    } else {
                        rows = rows + '<tr><th>'+i18n.t('ms_office.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
                }
            }
        
            $('#ms_office-tab')
                .append($('<h2>')
                    .append(i18n.t('ms_office.ms_office')))
                .append($('<h4>')
                    .append($('<i>')))
                .append($('<div style="max-width:400px;">')
                    .append($('<table>')
                        .addClass('table table-striped table-condensed')
                        .append($('<tbody>')
                            .append(rows))))
            
            // Registered apps block
            if (typeof d.registeredapplications !== "string"){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                                .addClass('fa fa-registered'))
                            .append(' '+i18n.t('ms_office.registeredapplications')))
                    .append($('<div style="max-width:400px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_reg_apps))))
            } else {
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                                .addClass('fa fa-registered'))
                            .append(' '+i18n.t('ms_office.registeredapplications')))
                    .append($('<div style="max-width:1050px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_reg_apps))))
            }

            // MAU block
            if (rows_mau !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-clock-o'))
                        .append(' AutoUpdate'))
                    .append($('<div style="max-width:475px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_mau))))
            }
            
            // Excel block
            if (rows_excel !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-file-excel-o'))
                        .append(' Excel '+d.excel_office_generation))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_excel))))
            }
            
            // PowerPoint block
            if (rows_ppt !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-file-powerpoint-o'))
                        .append(' PowerPoint '+d.powerpoint_office_generation))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_ppt))))
            }
                        
            // Outlook block
            if (rows_outlook !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-envelope'))
                        .append(' Outlook '+d.outlook_office_generation))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_outlook))))
            }
                        
            // OneNote block
            if (rows_onenote !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-sticky-note'))
                        .append(' OneNote '+d.onenote_office_generation))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_onenote))))
            }

            // Word block
            if (rows_word !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-file-word-o'))
                        .append(' Word '+d.word_office_generation))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_word))))
            }
            
            // OneDrive block
            if (rows_onedrive !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-cloud'))
                        .append(' OneDrive'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_onedrive))))
            }
            
            // MS RDP block
            if (rows_reportdestkop !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-desktop'))
                        .append(' Remote Desktop'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_reportdestkop))))
            }
            
            // SfB block
            if (rows_sfb !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-skype'))
                        .append(' Skype for Business'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_sfb))))
            }
            
            // Edge block
            if (rows_edge !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-edge'))
                        .append(' Edge'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_edge))))
            }
            
            // Teams block
            if (rows_teams !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-users'))
                        .append(' Teams'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_teams))))
            }
            
            // Company Portal block
            if (rows_company_portal !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-building-o'))
                        .append(' Company Portal'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_company_poral))))
            }
            
            // ATP Defender block
            if (rows_defender !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-shield'))
                        .append(' ATP Defender'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_defender))))
            }

            // Yammer block
            if (rows_yammer !== ''){
                $('#ms_office-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-yoast'))
                        .append(' Yammer'))
                    .append($('<div style="max-width:350px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows_yammer))))
            }
        })
    });
});
</script>
