<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MsOfficeAddTeams extends Migration
{
    private $tableName = 'ms_office';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('teams_app_version')->nullable();
            $table->boolean('teams_mas')->nullable();
            
            $table->index('teams_app_version');
            $table->index('teams_mas');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('teams_app_version');
            $table->dropColumn('teams_mas');           
        });
    }
}
