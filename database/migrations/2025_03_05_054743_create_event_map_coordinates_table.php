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
        Schema::create('event_map_coordinates', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Заголовок сохраненных координат');
            $table->string('description')->default('')->comment('Описание места');
            $table->double('address_latitude')->comment('Широта');
            $table->double('address_longitude')->comment('Долгота');

            $table->unsignedBigInteger('travel_id')->comment('Путешествие');
            $table->unsignedBigInteger('travel_event_id')->nullable()->comment('Событие путешествия');

            $table->index('travel_id', 'travel_coordinates_idx');
            $table->index('travel_event_id', 'travel_event_coordinates_idx');

            $table->foreign('travel_id', 'travel_coordinates_fk')->references('id')->on('travel')->onDelete('cascade');
            $table->foreign('travel_event_id', 'travel_event_coordinates_fk')->references('id')->on('travel_events')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_map_coordinates');
    }
};
