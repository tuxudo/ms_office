<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MsOfficeAddO365UserAccounts extends Migration
{
    private $tableName = 'ms_office';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->text('o365_user_accounts')->nullable();
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('o365_user_accounts');
        });
    }
}
