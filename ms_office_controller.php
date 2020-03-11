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
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `howtocheck` = 'Manual' THEN 1 END) AS 'Manual',
                        COUNT(CASE WHEN `howtocheck` = 'AutomaticCheck' THEN 1 END) AS 'AutomaticCheck',
                        COUNT(CASE WHEN `howtocheck` = 'AutomaticDownload' THEN 1 END) AS 'AutomaticDownload'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
    /**
    * Retrieve MAU Channel data in json format
    *
    * @return void
    * @author joncrain
    **/
    public function get_channel()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `channelname` = 'InsiderFast' THEN 1 END) AS 'InsiderFast',
                        COUNT(CASE WHEN `channelname` = 'External' THEN 1 END) AS 'InsiderSlow',
                        COUNT(CASE WHEN `channelname` = 'Production' THEN 1 END) AS 'Production'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }

    /**
    * Retrieve license data in json format
    *
    * @return void
    * @author joncrain
    **/
    public function get_license_type()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(o365_detected) AS 'o365',
                        COUNT(CASE WHEN `vl_license_type` like '%Volume%' THEN 1 END) AS 'vl',
                        COUNT(CASE WHEN `vl_license_type` like '%Home%' THEN 1 END) AS 'retail'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
    /**
    * Retrieve msupdate_check_enabled data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_msupdate_check_enabled()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(CASE WHEN `msupdate_check_enabled` = '1' THEN 1 END) AS 'enabled',
                        COUNT(CASE WHEN `msupdate_check_enabled` = '0' THEN 0 END) AS 'disabled'
                        from ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
        
//        print_r($sql);
    }

    /**
    * Volume license version/Word app version mismatches
    *
    * @return void
    * @author tuxuso
    **/
    public function get_license_mismatch()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(CASE WHEN `vl_license_type` like '%2011%' AND `word_office_generation` != '2011' THEN 1 END) AS 'v2011',
                        COUNT(CASE WHEN `vl_license_type` like '%2016%' AND `word_office_generation` != '2016' THEN 1 END) AS 'v2016',
                        COUNT(CASE WHEN `vl_license_type` like '%2019%' AND `word_office_generation` != '2019' THEN 1 END) AS 'v2019'
                        FROM ms_office
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');        
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_mas()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(CASE WHEN `excel_mas` = '1' THEN 1 END) AS 'excel_mas_yes',
                    COUNT(CASE WHEN `excel_mas` = '0' THEN 1 END) AS 'excel_mas_no',
                    COUNT(CASE WHEN `teams_mas` = '1' THEN 1 END) AS 'teams_mas_yes',
                    COUNT(CASE WHEN `teams_mas` = '0' THEN 1 END) AS 'teams_mas_no',
                    COUNT(CASE WHEN `onedrive_mas` = '1' THEN 1 END) AS 'onedrive_mas_yes',
                    COUNT(CASE WHEN `onedrive_mas` = '0' THEN 1 END) AS 'onedrive_mas_no',
                    COUNT(CASE WHEN `onenote_mas` = '1' THEN 1 END) AS 'onenote_mas_yes',
                    COUNT(CASE WHEN `onenote_mas` = '0' THEN 1 END) AS 'onenote_mas_no',
                    COUNT(CASE WHEN `outlook_mas` = '1' THEN 1 END) AS 'outlook_mas_yes',
                    COUNT(CASE WHEN `outlook_mas` = '0' THEN 1 END) AS 'outlook_mas_no',
                    COUNT(CASE WHEN `powerpoint_mas` = '1' THEN 1 END) AS 'powerpoint_mas_yes',
                    COUNT(CASE WHEN `powerpoint_mas` = '0' THEN 1 END) AS 'powerpoint_mas_no',
                    COUNT(CASE WHEN `remote_desktop_mas` = '1' THEN 1 END) AS 'remote_desktop_mas_yes',
                    COUNT(CASE WHEN `remote_desktop_mas` = '0' THEN 1 END) AS 'remote_desktop_mas_no',
                    COUNT(CASE WHEN `word_mas` = '1' THEN 1 END) AS 'word_mas_yes',
                    COUNT(CASE WHEN `word_mas` = '0' THEN 1 END) AS 'word_mas_no'
                    FROM ms_office
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE
						".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_generation()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $queryobj = new Ms_office_model();
        $sql = "SELECT COUNT(CASE WHEN `word_office_generation` = '2011' THEN 1 END) AS 'word_gen_2011',
                    COUNT(CASE WHEN `word_office_generation` = '2016' THEN 1 END) AS 'word_gen_2016',
                    COUNT(CASE WHEN `word_office_generation` = '2019' THEN 1 END) AS 'word_gen_2019',
                    COUNT(CASE WHEN `excel_office_generation` = '2011' THEN 1 END) AS 'excel_gen_2011',
                    COUNT(CASE WHEN `excel_office_generation` = '2016' THEN 1 END) AS 'excel_gen_2016',
                    COUNT(CASE WHEN `excel_office_generation` = '2019' THEN 1 END) AS 'excel_gen_2019',
                    COUNT(CASE WHEN `powerpoint_office_generation` = '2011' THEN 1 END) AS 'powerpoint_gen_2011',
                    COUNT(CASE WHEN `powerpoint_office_generation` = '2016' THEN 1 END) AS 'powerpoint_gen_2016',
                    COUNT(CASE WHEN `powerpoint_office_generation` = '2019' THEN 1 END) AS 'powerpoint_gen_2019',
                    COUNT(CASE WHEN `outlook_office_generation` = '2011' THEN 1 END) AS 'outlook_gen_2011',
                    COUNT(CASE WHEN `outlook_office_generation` = '2016' THEN 1 END) AS 'outlook_gen_2016',
                    COUNT(CASE WHEN `outlook_office_generation` = '2019' THEN 1 END) AS 'outlook_gen_2019',
                    COUNT(CASE WHEN `onenote_office_generation` = '2016' THEN 1 END) AS 'onenote_gen_2016',
                    COUNT(CASE WHEN `onenote_office_generation` = '2019' THEN 1 END) AS 'onenote_gen_2019'
                    FROM ms_office
                    LEFT JOIN reportdata USING (serial_number)
                    WHERE
						".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT `channelname`, `howtocheck`, `lastcheckforupdates`, `manifestserver`, `updatecache`, `msupdate_check_enabled`, `o365_license_count`, `o365_detected`, `shared_o365_license`, `enablecheckforupdatesbutton`, `sendalltelemetryenabled`, `disableinsidercheckbox`, `startdaemononapplaunch`, `vl_license_type`, `mau_privilegedhelpertool`, `autoupdate_app_version`, `autoupdate_mas`, `company_portal_app_version`, `atp_defender_app_version`, `edge_app_version`, `excel_app_version`, `excel_mas`, `excel_office_generation`, `onedrive_app_version`, `onedrive_mas`, `onenote_app_version`, `onenote_mas`, `onenote_office_generation`, `outlook_app_version`, `outlook_mas`, `outlook_office_generation`, `powerpoint_app_version`, `powerpoint_mas`, `powerpoint_office_generation`, `remote_desktop_app_version`, `remote_desktop_mas`, `skype_for_business_app_version`, `teams_app_version`, `teams_mas`, `word_app_version`, `word_mas`, `word_office_generation`, `yammer_app_version`, `registeredapplications`
                        FROM ms_office 
                        WHERE serial_number = '$serial_number'";
        
        $queryobj = new Ms_office_model();
        $ms_office_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $ms_office_tab)))); 
    }
} // END class Ms_office_controller