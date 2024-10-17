<?php
/**
 * ms_office module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Ms_office_controller extends Module_controller
{

    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    /**
    * Default method
    *
    * @author AvB
    **/
    public function index()
    {
        echo "You've loaded the ms_office module!";
    }

    /**
    * Retrieve MAU how to check data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_how_to_check()
    {
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `howtocheck` = 'Manual' THEN 1 END) AS 'Manual',
                        COUNT(CASE WHEN `howtocheck` = 'AutomaticCheck' THEN 1 END) AS 'AutomaticCheck',
                        COUNT(CASE WHEN `howtocheck` = 'AutomaticDownload' THEN 1 END) AS 'AutomaticDownload'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');
        $out = [];
        $queryobj = new Ms_office_model();
        foreach($queryobj->query($sql)[0] as $label => $value){
                $out[] = ['label' => $label, 'count' => $value];
        }

        jsonView($out);
    }

    /**
    * Retrieve MAU Channel data in json format
    *
    * @return void
    * @author joncrain
    **/
    public function get_channel()
    {
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `channelname` = 'InsiderFast' OR `channelname` = 'Beta' THEN 1 END) AS 'Beta',
                        COUNT(CASE WHEN `channelname` = 'External' OR `channelname` = 'Preview' THEN 1 END) AS 'Preview',
                        COUNT(CASE WHEN `channelname` = 'Production' OR `channelname` = 'Current' THEN 1 END) AS 'Current'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');

        $out = [];
        $queryobj = new Ms_office_model();
        foreach($queryobj->query($sql)[0] as $label => $value){
                $out[] = ['label' => $label, 'count' => $value];
        }

        jsonView($out);
    }

    /**
    * Retrieve license data in json format
    *
    * @return void
    * @author joncrain
    **/
    public function get_license_type()
    {
        $sql = "SELECT COUNT(1) as total,
                        COUNT(o365_detected) AS 'o365',
                        COUNT(CASE WHEN `vl_license_type` like '%Volume%' THEN 1 END) AS 'vl',
                        COUNT(CASE WHEN `vl_license_type` like '%Home%' THEN 1 END) AS 'retail'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');

        $out = [];
        $queryobj = new Ms_office_model();
        foreach($queryobj->query($sql)[0] as $label => $value){
                $out[] = ['label' => $label, 'count' => $value];
        }

        jsonView($out);
    }

    /**
    * Retrieve msupdate_check_enabled data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_msupdate_check_enabled()
    {
        $sql = "SELECT COUNT(CASE WHEN `msupdate_check_enabled` = '1' THEN 1 END) AS 'enabled',
                        COUNT(CASE WHEN `msupdate_check_enabled` = '0' THEN 0 END) AS 'disabled'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');

        $out = [];
        $queryobj = new Ms_office_model();
        foreach($queryobj->query($sql)[0] as $label => $value){
                $out[] = ['label' => $label, 'count' => $value];
        }

        jsonView($out);
    }

    /**
    * Volume license version/Word app version mismatches
    *
    * @return void
    * @author tuxudo
    **/
    public function get_license_mismatch()
    {
        $sql = "SELECT COUNT(CASE WHEN `vl_license_type` like '%2024%' AND `word_office_generation` != '2024' THEN 1 END) AS 'v2024',
                        COUNT(CASE WHEN `vl_license_type` like '%2021%' AND `word_office_generation` != '2021' THEN 1 END) AS 'v2021',
                        COUNT(CASE WHEN `vl_license_type` like '%2019%' AND `word_office_generation` != '2019' THEN 1 END) AS 'v2019',
                        COUNT(CASE WHEN `vl_license_type` like '%2016%' AND `word_office_generation` != '2016' THEN 1 END) AS 'v2016',
                        COUNT(CASE WHEN `vl_license_type` like '%2011%' AND `word_office_generation` != '2011' THEN 1 END) AS 'v2011'
                        FROM ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE ".get_machine_group_filter('');

        $out = [];
        $queryobj = new Ms_office_model();
        foreach($queryobj->query($sql)[0] as $label => $value){
                $out[] = ['label' => $label, 'count' => $value];
        }

        jsonView($out);
    }

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_mas($app)
    {
        $app = preg_replace("/[^a-z0-9_]]/", '', $app);

        $sql = "SELECT COUNT(CASE WHEN `".$app."_mas` = '1' THEN 1 END) AS 'yes',
                    COUNT(CASE WHEN `".$app."_mas` = '0' THEN 1 END) AS 'no'
                    FROM ms_office
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE ".get_machine_group_filter('');

        $out = [];
        $queryobj = new Ms_office_model();
        foreach($queryobj->query($sql)[0] as $label => $value){
                $out[] = ['label' => $label, 'count' => $value];
        }

        jsonView($out);
    }

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_generation($app)
    {
        $app = preg_replace("/[^a-z0-9_]]/", '', $app);

        $sql = "SELECT COUNT(CASE WHEN `".$app."_office_generation` = '2011' THEN 1 END) AS 'v2011',
                    COUNT(CASE WHEN `".$app."_office_generation` = '2016' THEN 1 END) AS 'v2016',
                    COUNT(CASE WHEN `".$app."_office_generation` = '2019' THEN 1 END) AS 'v2019',
                    COUNT(CASE WHEN `".$app."_office_generation` = '2021' THEN 1 END) AS 'v2021',
                    COUNT(CASE WHEN `".$app."_office_generation` = '2024' THEN 1 END) AS 'v2024'
                    FROM ms_office
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE ".get_machine_group_filter('');

        $out = [];
        $queryobj = new Ms_office_model();
        foreach($queryobj->query($sql)[0] as $label => $value){
                $out[] = ['label' => $label, 'count' => $value];
        }

        jsonView($out);
    }

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]]/", '', $serial_number);

        $sql = "SELECT `channelname`, `howtocheck`, `lastcheckforupdates`, `manifestserver`, `updatecache`, `msupdate_check_enabled`, `o365_license_count`, `o365_detected`, `o365_user_accounts`, `shared_o365_license`, `enablecheckforupdatesbutton`, `sendalltelemetryenabled`, `disableinsidercheckbox`, `startdaemononapplaunch`, `vl_license_type`, `mau_privilegedhelpertool`, `autoupdate_app_version`, `autoupdate_mas`, `company_portal_app_version`, `atp_defender_app_version`, `edge_app_version`, `excel_app_version`, `excel_mas`, `excel_office_generation`, `onedrive_app_version`, `onedrive_mas`, `onenote_app_version`, `onenote_mas`, `onenote_office_generation`, `outlook_app_version`, `outlook_mas`, `outlook_office_generation`, `powerpoint_app_version`, `powerpoint_mas`, `powerpoint_office_generation`, `remote_desktop_app_version`, `remote_desktop_mas`, `skype_for_business_app_version`, `teams_app_version`, `teams_mas`, `word_app_version`, `word_mas`, `word_office_generation`, `yammer_app_version`, `registeredapplications`
                        FROM ms_office 
                        LEFT JOIN reportdata USING (serial_number)
                        ".get_machine_group_filter()."
                        AND serial_number = '$serial_number'";

        $queryobj = new Ms_office_model();
        jsonView($queryobj->query($sql));
    }
} // END class Ms_office_controller