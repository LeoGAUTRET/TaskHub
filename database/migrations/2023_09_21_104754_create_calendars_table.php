<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->string('calendar_event_id')->primary()->unique();
            $table->text('title');//summary (titre)
            $table->text('description')->nullable();//description
            $table->string('location')->nullable();//location
            $table->integer('color_id')->nullable();//colorId
            $table->dateTime('start');//start
            $table->dateTime('end');//end
            $table->string('etag');//etag
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
