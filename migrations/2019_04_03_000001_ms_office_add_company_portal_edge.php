<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MsOfficeAddCompanyPortalEdge extends Migration
{
    private $tableName = 'ms_office';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('company_portal_app_version')->nullable();
            $table->string('edge_app_version')->nullable();
            
            $table->index('company_portal_app_version');
            $table->index('edge_app_version');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('company_portal_app_version');
            $table->dropColumn('edge_app_version');
        });
    }
}
