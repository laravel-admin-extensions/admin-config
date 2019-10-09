<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTable extends Migration
{
    public function up()
    {
        Schema::create('admin_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('value');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_config');
    }
}
