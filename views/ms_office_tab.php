<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/ms_office/ms_office" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/ms_office/ms_office_report" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<div id="ms_office-tab"></div>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/ms_office/get_tab_data/' + serialNumber, function(data){
        var skipThese = ['id','serial_number'];
        var $msOfficeTab = $('#ms_office-tab'); // Cache the jQuery selector

        $.each(data, function(i, d){
            var html = ''; // Initialize an empty string for HTML

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
            var rows_copilot = ''
            var rows_reg_apps = '<tr><td>'+i18n.t('ms_office.no_registeredapplications')+'</td><td></td><td></td><td></td><td></td><td></td></tr>'
            var rdp_win_app = "win"

            for (var prop in d) {
                // Skip skipThese and empty values
                if (skipThese.indexOf(prop) !== -1 || d[prop] === '' || d[prop] === null) {
                    continue;
                }

                const propTranslation = i18n.t('ms_office.' + prop);
                const value = d[prop];
                let row = '';

                // Format enabled/disabled
                if (prop === 'enablecheckforupdatesbutton' || prop === 'sendalltelemetryenabled') {
                    rows_mau += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'enabled' : 'disabled') + '</td></tr>';

                // Format enabled/disabled insider checkbox
                } else if (prop === 'disableinsidercheckbox') {
                    rows_mau += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'disabled' : 'enabled') + '</td></tr>';

                // Format helper tool
                } else if (prop === 'mau_privilegedhelpertool') {
                    rows_mau += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'ms_office.installed' : 'ms_office.not_installed') + '</td></tr>';

                // Format yes/no
                } else if (prop === 'shared_o365_license' || prop === 'o365_detected') {
                    rows += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';

                // Format yes/no rows_mau
                } else if (prop === 'startdaemononapplaunch' || prop === 'autoupdate_mas') {
                    rows_mau += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                // } else if (prop === 'msupdate_check_enabled') {
                //     rows_mau += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';

                // Format MAS apps yes/no
                } else if (prop === 'excel_mas') {
                    rows_excel += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'word_mas') {
                    rows_word += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'powerpoint_mas') {
                    rows_ppt += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'outlook_mas') {
                    rows_outlook += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'onenote_mas') {
                    rows_onenote += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'onedrive_mas') {
                    rows_onedrive += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'remote_desktop_mas') {
                    rows_reportdestkop += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'skype_for_business_mas') {
                    rows_sfb += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                } else if (prop === 'teams_mas') {
                    rows_teams += '<tr><th>' + propTranslation + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';

                // Format O365 user accounts
                } else if (prop === 'o365_user_accounts' && value !== '') {
                    rows += '<tr><th>' + propTranslation + '</th><td>' + value.replaceAll(', ', '<br>') + '</td></tr>';

                // AutoUpdate properties
                } else if (prop.indexOf('autoupdate_') > -1 || ['channelname', 'howtocheck', 'lastcheckforupdates', 'manifestserver', 'updatecache'].includes(prop)) {
                    if (prop === 'lastcheckforupdates' && !isNaN(value) && value !== '') {
                        const date = new Date(value * 1000);
                        rows_mau += '<tr><th>' + propTranslation + '</th><td><span title="' + moment(date).format('llll') + '">' + moment(date).fromNow() + '</span></td></tr>';
                    } else {
                        rows_mau += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                    }

                // App-specific properties
                } else if (prop.indexOf('excel_') > -1) {
                    rows_excel += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('outlook_') > -1) {
                    rows_outlook += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('onenote_') > -1) {
                    rows_onenote += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('onedrive_') > -1) {
                    rows_onedrive += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('powerpoint_') > -1) {
                    rows_ppt += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('remote_desktop_') > -1) {
                    if (prop === 'remote_desktop_app_version' && value < '11') {
                        rdp_win_app = 'rdp';
                    }
                    rows_reportdestkop += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('skype_for_business_') > -1) {
                    rows_sfb += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('teams_') > -1) {
                    rows_teams += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('word_') > -1) {
                    rows_word += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('company_portal_') > -1) {
                    rows_company_portal += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('edge_') > -1) {
                    rows_edge += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('atp_defender_') > -1) {
                    rows_defender += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('yammer_app_') > -1) {
                    rows_yammer += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                } else if (prop.indexOf('copilot_app_') > -1) {
                    rows_copilot += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';

                // Registered applications
                } else if (prop === 'registeredapplications') {
                    const reg_apps_data = JSON.parse(value);
                    rows_reg_apps = '<tr><th>' + i18n.t('ms_office.application') + '</th><th>' + i18n.t('ms_office.application_id') + '</th><th>' + i18n.t('ms_office.title') + '</th><th>' + i18n.t('ms_office.versionondisk') + '</th><th>' + i18n.t('ms_office.baseline_version') + '</th><th>' + i18n.t('ms_office.update_version') + '</th><th>' + i18n.t('ms_office.date') + '</th></tr>';
                    
                    $.each(reg_apps_data, function(i, d) {
                        rows_reg_apps += '<tr><td>' + i + '</td><td>' + 
                            (d['application_id'] || '') + '</td><td>' + 
                            (d['title'] || '') + '</td><td>' + 
                            (d['versionondisk'] || '') + '</td><td>' + 
                            (d['baseline_version'] || '') + '</td><td>' + 
                            (d['update_version'] || '') + '</td><td>' + 
                            (d['date'] || '') + '</td></tr>';
                    });

                // Default case
                } else {
                    rows += '<tr><th>' + propTranslation + '</th><td>' + value + '</td></tr>';
                }
            }

            // Generate the main block with icon
            html += `<h2><i class="fa fa-windows"></i> ${i18n.t('ms_office.ms_office')}</h2>
                     <h4><i></i></h4>
                     <div style="max-width:550px;">
                         <table class="table table-striped table-condensed">
                             <tbody>${rows}</tbody>
                         </table>
                     </div>`;

            // Registered apps block
            var regAppsWidth = (typeof d.registeredapplications !== "string") ? '400px' : '1050px';
            html += `<h4><i class="fa fa-registered"></i> ${i18n.t('ms_office.registeredapplications')}</h4>
                     <div style="max-width:${regAppsWidth};">
                         <table class="table table-striped table-condensed">
                             <tbody>${rows_reg_apps}</tbody>
                         </table>
                     </div>`;

            // MAU block
            if (rows_mau !== ''){
                html += `<h4><i class="fa fa-clock-o"></i> AutoUpdate</h4>
                         <div style="max-width:475px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_mau}</tbody>
                             </table>
                         </div>`;
            }

            // Excel block
            if (rows_excel !== ''){
                html += `<h4><i class="fa fa-file-excel-o"></i> Excel ${d.excel_office_generation}</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_excel}</tbody>
                             </table>
                         </div>`;
            }

            // PowerPoint block
            if (rows_ppt !== ''){
                html += `<h4><i class="fa fa-file-powerpoint-o"></i> PowerPoint ${d.powerpoint_office_generation}</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_ppt}</tbody>
                             </table>
                         </div>`;
            }

            // Outlook block
            if (rows_outlook !== ''){
                html += `<h4><i class="fa fa-envelope"></i> Outlook ${d.outlook_office_generation}</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_outlook}</tbody>
                             </table>
                         </div>`;
            }

            // OneNote block
            if (rows_onenote !== ''){
                html += `<h4><i class="fa fa-sticky-note"></i> OneNote ${d.onenote_office_generation}</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_onenote}</tbody>
                             </table>
                         </div>`;
            }

            // Word block
            if (rows_word !== ''){
                html += `<h4><i class="fa fa-file-word-o"></i> Word ${d.word_office_generation}</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_word}</tbody>
                             </table>
                         </div>`;
            }

            // OneDrive block
            if (rows_onedrive !== ''){
                html += `<h4><i class="fa fa-cloud"></i> OneDrive</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_onedrive}</tbody>
                             </table>
                         </div>`;
            }

            // MS RDP block
            if (rows_reportdestkop !== '' && rdp_win_app == "rdp"){
                html += `<h4><i class="fa fa-desktop"></i> Remote Desktop</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_reportdestkop}</tbody>
                             </table>
                         </div>`;
            }

            // Windows App (eww) block
            if (rows_reportdestkop !== '' && rdp_win_app == "win"){
                html += `<h4><i class="fa fa-windows"></i> Windows App</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_reportdestkop}</tbody>
                             </table>
                         </div>`;
            }

            // SfB block
            if (rows_sfb !== ''){
                html += `<h4><i class="fa fa-skype"></i> Skype for Business</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_sfb}</tbody>
                             </table>
                         </div>`;
            }

            // Edge block
            if (rows_edge !== ''){
                html += `<h4><i class="fa fa-edge"></i> Edge</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_edge}</tbody>
                             </table>
                         </div>`;
            }

            // Teams block
            if (rows_teams !== ''){
                html += `<h4><i class="fa fa-users"></i> Teams</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_teams}</tbody>
                             </table>
                         </div>`;
            }

            // Company Portal block
            if (rows_company_portal !== ''){
                html += `<h4><i class="fa fa-building-o"></i> Company Portal</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_company_portal}</tbody>
                             </table>
                         </div>`;
            }

            // ATP Defender block
            if (rows_defender !== ''){
                html += `<h4><i class="fa fa-shield"></i> ATP Defender</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_defender}</tbody>
                             </table>
                         </div>`;
            }

            // Yammer block
            if (rows_yammer !== ''){
                html += `<h4><i class="fa fa-yoast"></i> Yammer</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_yammer}</tbody>
                             </table>
                         </div>`;
            }

            // Copilot block
            if (rows_copilot !== ''){
                html += `<h4><i class="fa fa-robot"></i> Copilot</h4>
                         <div style="max-width:350px;">
                             <table class="table table-striped table-condensed">
                                 <tbody>${rows_copilot}</tbody>
                             </table>
                         </div>`;
            }

            // Append the constructed HTML to the DOM once
            $msOfficeTab.append(html);
        });
    });
});
</script>
