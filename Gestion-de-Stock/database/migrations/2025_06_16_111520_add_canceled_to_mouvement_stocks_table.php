<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mouvement_stocks', function (Blueprint $table) {
            $table->boolean('canceled')->default(false)->after('utilisateur_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mouvement_stocks', function (Blueprint $table) {
            $table->dropColumn('canceled');
        });
    }
};
