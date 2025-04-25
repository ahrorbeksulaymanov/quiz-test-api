<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description")->nullable();
            $table->integer("order")->nullable();
            $table->string("photo")->nullable();
            $table->integer("ball");
            $table->integer("duration");
            $table->foreignId("age_category_id")->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
