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
        Schema::create('travel', function (Blueprint $table) {
            $table->id();
            $table->string('travel_title')->comment('Заголовок');
            $table->text('short_description')->default('')->comment('Краткое описание');
            $table->string('travel_main_photo')->nullable()->comment('Обложка');
            $table->date('start_at')->comment('Дата начала');
            $table->date('end_at')->nullable()->comment('Дата окончания');

            $table->unsignedBigInteger('user_id')->comment('Пользователь');

            $table->index('user_id', 'travel_user_idx');

            $table->foreign('user_id', 'travel_user_fk')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel');
    }
};
