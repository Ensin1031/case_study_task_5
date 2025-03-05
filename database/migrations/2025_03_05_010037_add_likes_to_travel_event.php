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
        Schema::table('travel_events', function (Blueprint $table) {
            $table->enum('event_score', [1, 2, 3, 4, 5])->default(1)->comment('Оценка события');  // 1 - Очень не понравилось, 2 - Не понравилось, 3 - Сойдет, средне, 4 - Понравилось, 5 - Очень понравилось
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_events', function (Blueprint $table) {
            $table->dropColumn('event_score');
        });
    }
};
