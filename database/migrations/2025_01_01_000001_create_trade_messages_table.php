<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('trade_messages')) {
            Schema::create('trade_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trade_id');
                $table->unsignedBigInteger('sender_id');
                $table->text('message');
                $table->timestamps();

                $table->foreign('trade_id')->references('id')->on('trades')->onDelete('cascade');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('trade_messages');
    }
};
