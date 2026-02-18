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
 * @property ?float $alpha_acid_min
 * @property ?float $alpha_acid_max
 * @property ?float $beta_acid_min
 * @property ?float $beta_acid_max
 * @property ?float $cohumulone_min
 * @property ?float $cohumulone_max
 * @property ?float $total_oil_min
 * @property ?float $total_oil_max
 * @property ?float $polyphenol_min
 * @property ?float $polyphenol_max
 * @property ?float $xanthohumol_min
 * @property ?float $xanthohumol_max
 * @property ?float $farnesene_min
 * @property ?float $farnesene_max
 * @property ?float $linalool_min
 * @property ?float $linalool_max
 * @property ?float $aroma_citrusy
 * @property ?float $aroma_fruity
 * @property ?float $aroma_floral
 * @property ?float $aroma_herbal
 * @property ?float $aroma_spicy
 * @property ?float $aroma_resinous
 * @property ?float $aroma_sugarlike
 * @property ?float $aroma_miscellaneous
 * @property ?array $aroma_descriptors
 * @property ?array $substitutes
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
    ];
    protected $casts = [
        "aroma_descriptors" => "array",
        "substitutes" => "array",
    ];
}
