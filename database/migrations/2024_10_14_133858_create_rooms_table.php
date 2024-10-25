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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('waiting'); // 初期状態は待機
            $table->integer('player_count')->default(0);  // 初期の参加者数は0
            $table->integer('theme_id')->nullable(); //各部屋にテーマを保存
            $table->json
            ('player_order')->nullable();   //Hostが選択した順番を保存
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
