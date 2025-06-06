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
        Schema::create('libros', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('sinopsis')->nullable();
        $table->unsignedBigInteger('autor_id');
        $table->timestamps();

        $table->foreign('autor_id')
            ->references('id')
            ->on('autores')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
