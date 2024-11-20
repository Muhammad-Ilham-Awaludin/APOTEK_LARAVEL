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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['tablet', 'sirup', 'kapsul']);
            $table->integer('price');
            $table->integer('stock');
            $table->timestamps(); //akan menghasilkan dua column : created_at (auto terisi tanggal pembuatan data), update_at (auto terisi tanggal data di ubah)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
