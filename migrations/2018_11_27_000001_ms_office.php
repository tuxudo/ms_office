<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Msoffice extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('ms_office', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('channelname')->nullable();
            $table->string('howtocheck')->nullable();
            $table->bigint('lastcheckforupdates')->nullable();
            $table->string('manifestserver')->nullable();
            $table->integer('o365_license_count')->nullable();
            $table->boolean('o365_detected')->nullable();
            $table->boolean('shared_o365_license')->nullable();
            $table->boolean('enablecheckforupdatesbutton')->nullable();
            $table->boolean('sendalltelemetryenabled')->nullable();
            $table->boolean('disableinsidercheckbox')->nullable();
            $table->boolean('startdaemononapplaunch')->nullable();
            $table->string('updatecache')->nullable();
            $table->string('vl_license_type')->nullable();
            $table->longText('registeredapplications')->nullable();
            
            $table->boolean('mau_privilegedhelpertool')->nullable();
            $table->string('autoupdate_app_version')->nullable();
            $table->boolean('autoupdate_mas')->nullable();
            $table->string('excel_app_version')->nullable();
            $table->boolean('excel_mas')->nullable();
            $table->integer('excel_office_generation')->nullable();
            $table->string('onedrive_app_version')->nullable();
            $table->boolean('onedrive_mas')->nullable();
            $table->string('onenote_app_version')->nullable();
            $table->boolean('onenote_mas')->nullable();
            $table->integer('onenote_office_generation')->nullable();
            $table->string('outlook_app_version')->nullable();
            $table->boolean('outlook_mas')->nullable();
            $table->integer('outlook_office_generation')->nullable();
            $table->string('powerpoint_app_version')->nullable();
            $table->boolean('powerpoint_mas')->nullable();
            $table->integer('powerpoint_office_generation')->nullable();
            $table->string('remote_desktop_app_version')->nullable();
            $table->boolean('remote_desktop_mas')->nullable();
            $table->string('skype_for_business_app_version')->nullable();
            $table->boolean('skype_for_business_mas')->nullable();
            $table->string('word_app_version')->nullable();
            $table->boolean('word_mas')->nullable();
            $table->integer('word_office_generation')->nullable();

            $table->index('serial_number');
            $table->index('channelname');
            $table->index('howtocheck');
            $table->index('lastcheckforupdates');
            $table->index('manifestserver');
            $table->index('o365_license_count');
            $table->index('o365_detected');
            $table->index('enablecheckforupdatesbutton');
            $table->index('sendalltelemetryenabled');
            $table->index('disableinsidercheckbox');
            $table->index('startdaemononapplaunch');
            $table->index('updatecache');
            $table->index('vl_license_type');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('ms_office');
    }
}
