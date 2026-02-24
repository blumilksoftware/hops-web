<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create("hops", function (Blueprint $table): void {
            $table->id();
            $table->string("name")->unique();
            $table->string("slug")->unique();
            $table->string("country")->nullable();
            $table->text("description")->nullable();
            $table->decimal("alpha_acid_min", places: 4)->nullable();
            $table->decimal("alpha_acid_max", places: 4)->nullable();
            $table->decimal("beta_acid_min", places: 4)->nullable();
            $table->decimal("beta_acid_max", places: 4)->nullable();
            $table->decimal("cohumulone_min", places: 4)->nullable();
            $table->decimal("cohumulone_max", places: 4)->nullable();
            $table->decimal("total_oil_min", places: 4)->nullable();
            $table->decimal("total_oil_max", places: 4)->nullable();
            $table->decimal("polyphenol_min", places: 4)->nullable();
            $table->decimal("polyphenol_max", places: 4)->nullable();
            $table->decimal("xanthohumol_min", places: 4)->nullable();
            $table->decimal("xanthohumol_max", places: 4)->nullable();
            $table->decimal("farnesene_min", places: 4)->nullable();
            $table->decimal("farnesene_max", places: 4)->nullable();
            $table->decimal("linalool_min", places: 4)->nullable();
            $table->decimal("linalool_max", places: 4)->nullable();
            $table->tinyInteger("aroma_citrusy")->nullable();
            $table->tinyInteger("aroma_fruity")->nullable();
            $table->tinyInteger("aroma_floral")->nullable();
            $table->tinyInteger("aroma_herbal")->nullable();
            $table->tinyInteger("aroma_spicy")->nullable();
            $table->tinyInteger("aroma_resinous")->nullable();
            $table->tinyInteger("aroma_sugarlike")->nullable();
            $table->tinyInteger("aroma_miscellaneous")->nullable();
            $table->json("aroma_descriptors")->nullable(); 
            $table->json("substitutes")->nullable();
            $table->string("bitterness")->nullable();
            $table->string("aromaticity")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("hops");
    }
};
