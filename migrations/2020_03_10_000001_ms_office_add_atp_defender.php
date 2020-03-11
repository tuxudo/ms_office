<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MsOfficeAddAtpDefender extends Migration
{
    private $tableName = 'ms_office';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('atp_defender_app_version')->nullable();
            $table->string('yammer_app_version')->nullable();
            
            $table->index('atp_defender_app_version');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('atp_defender_app_version');
            $table->dropColumn('yammer_app_version');
        });
    }
}
