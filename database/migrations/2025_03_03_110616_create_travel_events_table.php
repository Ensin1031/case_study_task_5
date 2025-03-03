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
        Schema::create('travel_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_title')->default('')->comment('Заголовок события');
            $table->text('event_description')->default('')->comment('Описание события');
            $table->dateTime('event_at')->comment('Дата и время события');
            $table->float('event_price')->default(0.00)->comment('Потрачено за событие');

            $table->unsignedBigInteger('travel_id')->comment('Путешествие');

            $table->index('travel_id', 'travel_event_idx');

            $table->foreign('travel_id', 'travel_event_fk')->references('id')->on('travel')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_events');
    }
};
