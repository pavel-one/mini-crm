<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(0);
            $table->integer('price')->default(0);
            $table->string('name');
            $table->string('url');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('time')->nullable();
            $table->string('description')->nullable();
            $table->text('full_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_clients');
    }
}
