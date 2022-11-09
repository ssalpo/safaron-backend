<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('go_time')->comment('Дата и время поездки');
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('car_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('free_places')->default(1)->comment('Количество свободных мест');
            $table->boolean('fast_reservation')->default(true)->comment('Мгновенное бронирование');
            $table->boolean('baggage_transportation')->default(true)->comment('Перевозка багажа');
            $table->text('description')->nullable()->comment('Комментарий к поездке');
            $table->integer('price')->default(0)->comment('Цена поездки');
            $table->tinyInteger('status')->default(\App\Models\Route::STATUS_ACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
};
