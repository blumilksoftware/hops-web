<?php

declare(strict_types=1);

namespace HopsWeb\Models;

use HopsWeb\Casts\RangeOrNumberCast;
use HopsWeb\ValueObjects\RangeOrNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property ?string $country
 * @property ?string $description
 * @property ?RangeOrNumber $alpha_acid
 * @property ?RangeOrNumber $beta_acid
 * @property ?RangeOrNumber $cohumulone
 * @property ?RangeOrNumber $total_oil
 * @property ?RangeOrNumber $polyphenol
 * @property ?RangeOrNumber $xanthohumol
 * @property ?RangeOrNumber $farnesene
 * @property ?RangeOrNumber $linalool
 * @property ?int $aroma_citrusy
 * @property ?int $aroma_fruity
 * @property ?int $aroma_floral
 * @property ?int $aroma_herbal
 * @property ?int $aroma_spicy
 * @property ?int $aroma_resinous
 * @property ?int $aroma_sugarlike
 * @property ?int $aroma_miscellaneous
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
        "alpha_acid",
        "beta_acid",
        "cohumulone",
        "total_oil",
        "polyphenol",
        "xanthohumol",
        "farnesene",
        "linalool",
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
        "alpha_acid" => RangeOrNumberCast::class,
        "beta_acid" => RangeOrNumberCast::class,
        "cohumulone" => RangeOrNumberCast::class,
        "total_oil" => RangeOrNumberCast::class,
        "polyphenol" => RangeOrNumberCast::class,
        "xanthohumol" => RangeOrNumberCast::class,
        "farnesene" => RangeOrNumberCast::class,
        "linalool" => RangeOrNumberCast::class,
        "aroma_descriptors" => "array",
        "substitutes" => "array",
    ];
}
