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
        Schema::create('travel_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_title')->default('')->comment('Подпись файла');
            $table->string('file_name')->comment('Имя файла');
            $table->string('url')->comment('URL файла');

            $table->unsignedBigInteger('travel_id')->comment('Путешествие');
            $table->unsignedBigInteger('travel_event_id')->nullable()->comment('Событие путешествия');

            $table->index('travel_id', 'image_travel_idx');
            $table->index('travel_event_id', 'image_travel_event_idx');

            $table->foreign('travel_id', 'image_travel_fk')->references('id')->on('travel')->onDelete('cascade');
            $table->foreign('travel_event_id', 'image_travel_event_fk')->references('id')->on('travel_events')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_images');
    }
};
