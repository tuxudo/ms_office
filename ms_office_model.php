<?php

use CFPropertyList\CFPropertyList;

class Ms_office_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'ms_office'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['channelname'] = '';
        $this->rs['howtocheck'] = '';
        $this->rs['lastcheckforupdates'] = '';
        $this->rs['manifestserver'] = '';
        $this->rs['o365_license_count'] = 0;
        $this->rs['o365_detected'] = 0;
        $this->rs['shared_o365_license'] = 0;
        $this->rs['enablecheckforupdatesbutton'] = 0;
        $this->rs['sendalltelemetryenabled'] = 0;
        $this->rs['disableinsidercheckbox'] = 0;
        $this->rs['startdaemononapplaunch'] = 0;
        $this->rs['updatecache'] = '';
        $this->rs['vl_license_type'] = '';
        $this->rs['registeredapplications'] = '';
        $this->rs['mau_privilegedhelpertool'] = '';
        $this->rs['autoupdate_app_version'] = '';
        $this->rs['autoupdate_mas'] = '';
        $this->rs['excel_app_version'] = '';
        $this->rs['excel_mas'] = '';
        $this->rs['excel_office_generation'] = '';
        $this->rs['onedrive_app_version'] = '';
        $this->rs['onedrive_mas'] = '';
        $this->rs['onenote_app_version'] = '';
        $this->rs['onenote_mas'] = '';
        $this->rs['onenote_office_generation'] = '';
        $this->rs['outlook_app_version'] = '';
        $this->rs['outlook_mas'] = '';
        $this->rs['outlook_office_generation'] = '';
        $this->rs['powerpoint_app_version'] = '';
        $this->rs['powerpoint_mas'] = '';
        $this->rs['powerpoint_office_generation'] = '';
        $this->rs['remote_desktop_app_version'] = '';
        $this->rs['remote_desktop_mas'] = '';
        $this->rs['skype_for_business_app_version'] = '';
        $this->rs['skype_for_business_mas'] = '';
        $this->rs['word_app_version'] = '';
        $this->rs['word_mas'] = '';
        $this->rs['word_office_generation'] = '';
        
        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial_number = $serial;
    }


    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing ms_office module: No data found");
        } else { 

            // Process incoming ms_office.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            foreach (array('channelname', 'howtocheck', 'lastcheckforupdates', 'manifestserver', 'o365_license_count', 'o365_detected', 'shared_o365_license', 'enablecheckforupdatesbutton', 'sendalltelemetryenabled', 'disableinsidercheckbox', 'startdaemononapplaunch', 'updatecache', 'vl_license_type', 'registeredapplications', 'mau_privilegedhelpertool', 'autoupdate_app_version', 'autoupdate_mas', 'excel_app_version', 'excel_mas', 'excel_office_generation', 'onedrive_app_version', 'onedrive_mas', 'onenote_app_version', 'onenote_mas', 'onenote_office_generation', 'outlook_app_version', 'outlook_mas', 'outlook_office_generation', 'powerpoint_app_version', 'powerpoint_mas', 'powerpoint_office_generation', 'remote_desktop_app_version', 'remote_desktop_mas', 'skype_for_business_app_version', 'skype_for_business_mas', 'word_app_version', 'word_mas', 'word_office_generation') as $item) {
                // If registeredapplications key, process it
                if ( array_key_exists($item, $plist) && $item == "registeredapplications" ) {
                    $this->$item = json_encode($plist[$item]);        
                }
                // Else if key exists and value is zero, set the db value to zero
                else if ( array_key_exists($item, $plist) && $plist[$item] === 0 ) {
                    $this->$item = 0;
                }
                // Else if key does not exist in $plist, null it
                else if (! array_key_exists($item, $plist) || $plist[$item] == '') {
                    $this->$item = null;
                // Set the db fields to be the same as those in the preference file
                } else {
                    $this->$item = $plist[$item];
                }
            }
            // Save the data, like a briefcase 
            $this->save(); 
        }
    }
}