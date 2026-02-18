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
            $table->float("alpha_acid_min")->nullable();
            $table->float("alpha_acid_max")->nullable();
            $table->float("beta_acid_min")->nullable();
            $table->float("beta_acid_max")->nullable();
            $table->float("cohumulone_min")->nullable();
            $table->float("cohumulone_max")->nullable();
            $table->float("total_oil_min")->nullable();
            $table->float("total_oil_max")->nullable();
            $table->float("polyphenol_min")->nullable();
            $table->float("polyphenol_max")->nullable();
            $table->float("xanthohumol_min")->nullable();
            $table->float("xanthohumol_max")->nullable();
            $table->float("farnesene_min")->nullable();
            $table->float("farnesene_max")->nullable();
            $table->float("linalool_min")->nullable();
            $table->float("linalool_max")->nullable();
            $table->float("aroma_citrusy")->nullable();
            $table->float("aroma_fruity")->nullable();
            $table->float("aroma_floral")->nullable();
            $table->float("aroma_herbal")->nullable();
            $table->float("aroma_spicy")->nullable();
            $table->float("aroma_resinous")->nullable();
            $table->float("aroma_sugarlike")->nullable();
            $table->float("aroma_miscellaneous")->nullable();
            $table->json("aroma_descriptors")->nullable(); 
            $table->json("substitutes")->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("hops");
    }
};
