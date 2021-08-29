<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTokenColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('reports', 'token'))
        {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropIndex('token_index');
                $table->dropColumn('token');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('token', 32)->nullable();
            $table->index('token', 'token_index');
        });
    }
}
