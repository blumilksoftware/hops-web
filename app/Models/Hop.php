<?php

declare(strict_types=1);

namespace HopsWeb\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property ?string $country
 * @property ?string $description
 * @property ?decimal $alpha_acid_min
 * @property ?decimal $alpha_acid_max
 * @property ?decimal $beta_acid_min
 * @property ?decimal $beta_acid_max
 * @property ?decimal $cohumulone_min
 * @property ?decimal $cohumulone_max
 * @property ?decimal $total_oil_min
 * @property ?decimal $total_oil_max
 * @property ?decimal $polyphenol_min
 * @property ?decimal $polyphenol_max
 * @property ?decimal $xanthohumol_min
 * @property ?decimal $xanthohumol_max
 * @property ?decimal $farnesene_min
 * @property ?decimal $farnesene_max
 * @property ?decimal $linalool_min
 * @property ?decimal $linalool_max
 * @property ?decimal $aroma_citrusy
 * @property ?decimal $aroma_fruity
 * @property ?decimal $aroma_floral
 * @property ?decimal $aroma_herbal
 * @property ?decimal $aroma_spicy
 * @property ?decimal $aroma_resinous
 * @property ?decimal $aroma_sugarlike
 * @property ?decimal $aroma_miscellaneous
 * @property ?array $aroma_descriptors
 * @property ?array $substitutes
 * @property ?string $bitterness
 * @property ?string $aromaticity
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Hop extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "country",
        "description",
        "alpha_acid_min",
        "alpha_acid_max",
        "beta_acid_min",
        "beta_acid_max",
        "cohumulone_min",
        "cohumulone_max",
        "total_oil_min",
        "total_oil_max",
        "polyphenol_min",
        "polyphenol_max",
        "xanthohumol_min",
        "xanthohumol_max",
        "farnesene_min",
        "farnesene_max",
        "linalool_min",
        "linalool_max",
        "aroma_citrusy",
        "aroma_fruity",
        "aroma_floral",
        "aroma_herbal",
        "aroma_spicy",
        "aroma_resinous",
        "aroma_sugarlike",
        "aroma_miscellaneous",
        "aroma_descriptors",
        "substitutes",
        "bitterness",
        "aromaticity",
    ];
    protected $casts = [
        "aroma_descriptors" => "array",
        "substitutes" => "array",
    ];
}
