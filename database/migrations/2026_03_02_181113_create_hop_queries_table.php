<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("hop_queries", function (Blueprint $table): void {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->json("query");
            $table->json("response")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("hop_queries");
    }
};
