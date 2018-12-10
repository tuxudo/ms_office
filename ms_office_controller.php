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
        $sql = "select COUNT(1) as total,
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
    public function get_license()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ms_office_model();
        $sql = "select COUNT(1) as total,
                        COUNT(o365_license_count) AS 'o365',
                        COUNT(CASE WHEN `vl_license_type` like '%Volume%' THEN 1 END) AS 'vl',
                        COUNT(CASE WHEN `vl_license_type` like '%Home%' THEN 1 END) AS 'retail'
                        from ms_office
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
        $sql = "select COUNT(CASE WHEN `word_office_generation` = '2016' THEN 1 END) AS 'word_gen_2016',
                    COUNT(CASE WHEN `word_office_generation` = '2019' THEN 1 END) AS 'word_gen_2019',
                    COUNT(CASE WHEN `excel_office_generation` = '2016' THEN 1 END) AS 'excel_gen_2016',
                    COUNT(CASE WHEN `excel_office_generation` = '2019' THEN 1 END) AS 'excel_gen_2019',
                    COUNT(CASE WHEN `powerpoint_office_generation` = '2016' THEN 1 END) AS 'powerpoint_gen_2016',
                    COUNT(CASE WHEN `powerpoint_office_generation` = '2019' THEN 1 END) AS 'powerpoint_gen_2019',
                    COUNT(CASE WHEN `outlook_office_generation` = '2016' THEN 1 END) AS 'outlook_gen_2016',
                    COUNT(CASE WHEN `powerpoint_office_generation` = '2019' THEN 1 END) AS 'outlook_gen_2019',
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
        
        $sql = "SELECT `channelname`, `howtocheck`, `lastcheckforupdates`, `manifestserver`, `updatecache`, `o365_license_count`, `o365_detected`, `shared_o365_license`, `enablecheckforupdatesbutton`, `sendalltelemetryenabled`, `disableinsidercheckbox`, `startdaemononapplaunch`, `vl_license_type`, `mau_privilegedhelpertool`, `autoupdate_app_version`, `autoupdate_mas`, `excel_app_version`, `excel_mas`, `excel_office_generation`, `onedrive_app_version`, `onedrive_mas`, `onenote_app_version`, `onenote_mas`, `onenote_office_generation`, `outlook_app_version`, `outlook_mas`, `outlook_office_generation`, `powerpoint_app_version`, `powerpoint_mas`, `powerpoint_office_generation`, `remote_desktop_app_version`, `remote_desktop_mas`, `skype_for_business_app_version`, `skype_for_business_mas`, `word_app_version`, `word_mas`, `word_office_generation`, `registeredapplications`
                        FROM ms_office 
                        WHERE serial_number = '$serial_number'";
        
        $queryobj = new Ms_office_model();
        $ms_office_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $ms_office_tab)))); 
    }
} // END class Ms_office_controller