<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->unique()->nullable();
            $table->datetime('min_date');
            $table->datetime('max_date');
            $table->float('progress')->default(0);
            $table->boolean('completed')->default(false);
            $table->boolean('errored')->default(false);
            $table->string('token', 32)->nullable();
            $table->integer('pid')->default(0);
            $table->boolean('notify')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            // Indexing
            $table->index(['completed', 'errored'], 'pending_index');
            $table->index('token', 'token_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
