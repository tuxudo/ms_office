<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MsOfficeAddMsupdateCheckEnabled extends Migration
{
    private $tableName = 'ms_office';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->boolean('msupdate_check_enabled')->nullable();
            
            $table->index('msupdate_check_enabled');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('msupdate_check_enabled');
        });
    }
}
