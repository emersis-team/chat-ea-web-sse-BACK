<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('lat',10,6);
            $table->decimal('lon',10,6);
            $table->decimal('alt',10,6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('position_messages');
    }
}
