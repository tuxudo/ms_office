Microsoft Office Module
==============

Gets data about MS Office applications, licensing, and updates on the clients.

To disable the msupdate binary parts, run the following on the client:
`sudo defautls write org.munkireport.ms_office msupdate_check_disabled -bool true`
`sudo defautls write org.munkireport.ms_office msupdate_config_disabled -bool true`

* channelname - VARCHAR(255) - Name of the channel MAU uses to check for updates
* howtocheck - VARCHAR(255) - How MAU checks for updates
* lastcheckforupdates - VARCHAR(255) - Local time of client when MAU last checked for updates
* manifestserver - VARCHAR(255) - Manifest server MAU uses, if set
* o365_license_count - INT(11) - How many Office 365 license were counted on client
* o365_detected - boolean - If at least one Office 365 license was detected
* shared_o365_license - boolean - Shared Office 365 license detected
* enablecheckforupdatesbutton - boolean - Check for updates button enabled
* sendalltelemetryenabled - boolean - MAU to send telemetry or not
* disableinsidercheckbox - boolean - If insider checkbox is disabled
* startdaemononapplaunch - boolean - MAU daemon launches on app start
* updatecache - VARCHAR(255) - MAU's update cache location, if set
* vl_license_type - VARCHAR(255) - Perpetual license type
* registeredapplications - longtext - JSON array of applications registered with MAU and if they have an update
* mau_privilegedhelpertool - boolean - If MAU helper tool is installed
* autoupdate_app_version - VARCHAR(255) - MAU version
* autoupdate_mas - boolean - If MAU is from MAS
* excel_app_version - VARCHAR(255) - Excel version
* excel_mas - boolean - If Excel is from MAS
* excel_office_generation - INT(11) - Excel generation (2016/2019)
* onedrive_app_version - VARCHAR(255) - OneDrive version
* onedrive_mas - boolean - If OneDrive is from MAS
* onenote_app_version - VARCHAR(255) - OneNote version
* onenote_mas - boolean - If OneNote is from MAS
* onenote_office_generation - INT(11) - OneNote generation (2016/2019)
* outlook_app_version - VARCHAR(255) - Outlook version
* outlook_mas - boolean - If Outlook is from MAS
* outlook_office_generation - INT(11) - Outlook generation (2016/2019)
* powerpoint_app_version - VARCHAR(255) - PowerPoint version
* powerpoint_mas - boolean - If PowerPoint is from MAS
* powerpoint_office_generation - INT(11) - PowerPoint generation (2016/2019)
* remote_desktop_app_version - VARCHAR(255) - Remote Desktop version
* remote_desktop_mas - boolean - If Remote Desktop is from MAS
* skype_for_business_app_version - VARCHAR(255) - Skype for Business version
* skype_for_business_mas - boolean - If Skype for Business is from MAS
* word_app_version - VARCHAR(255) - Word version
* word_mas - boolean - If Word is from MAS
* word_office_generation - INT(11) - Word generation (2016/2019)

