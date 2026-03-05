<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("agenda_results", function (Blueprint $table): void {
            $table->id();
            $table->foreignId("agenda_id")->constrained()->cascadeOnDelete();
            $table->json("parameters");
            $table->json("response")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("agenda_results");
    }
};
