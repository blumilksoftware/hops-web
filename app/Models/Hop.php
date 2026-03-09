<?php

declare(strict_types=1);

namespace HopsWeb\Models;

use HopsWeb\Casts\RangeOrNumberCast;
use HopsWeb\Enums\Aromaticity;
use HopsWeb\Enums\Bitterness;
use HopsWeb\Enums\HopDescriptor;
use HopsWeb\Enums\HopLineage;
use HopsWeb\Enums\HopMaturity;
use HopsWeb\Enums\Resistance;
use HopsWeb\ValueObjects\RangeOrNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property ?string $alt_name
 * @property ?string $country
 * @property ?string $description
 * @property ?array<HopDescriptor> $descriptors
 * @property ?array<HopLineage> $lineage
 * @property ?RangeOrNumber $alpha_acid
 * @property ?RangeOrNumber $beta_acid
 * @property ?RangeOrNumber $cohumulone
 * @property ?RangeOrNumber $total_oil
 * @property ?RangeOrNumber $polyphenol
 * @property ?RangeOrNumber $xanthohumol
 * @property ?RangeOrNumber $farnesene
 * @property ?RangeOrNumber $linalool
 * @property ?Aromaticity $thiols
 * @property ?int $aroma_citrusy
 * @property ?int $aroma_fruity
 * @property ?int $aroma_floral
 * @property ?int $aroma_herbal
 * @property ?int $aroma_spicy
 * @property ?int $aroma_resinous
 * @property ?int $aroma_sugarlike
 * @property ?int $aroma_misc
 * @property ?array<string> $aroma_descriptors
 * @property ?array{brewhouse: array<string>, dryhopping: array<string>} $substitutes
 * @property ?int $yield_min
 * @property ?int $yield_max
 * @property ?HopMaturity $maturity
 * @property ?Resistance $wilt_disease
 * @property ?Resistance $downy_mildew
 * @property ?Resistance $powdery_mildew
 * @property ?Resistance $aphid
 * @property ?Bitterness $bitterness
 * @property ?Aromaticity $aromaticity
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Hop extends Model
{
    use HasFactory;

    public const array RANGE_FIELDS = [
        "alpha_acid",
        "beta_acid",
        "cohumulone",
        "total_oil",
        "polyphenol",
        "xanthohumol",
        "farnesene",
        "linalool",
    ];

    protected $fillable = [
        "name",
        "slug",
        "alt_name",
        "country",
        "description",
        "descriptors",
        "lineage",
        "alpha_acid",
        "beta_acid",
        "cohumulone",
        "total_oil",
        "polyphenol",
        "xanthohumol",
        "farnesene",
        "linalool",
        "thiols",
        "aroma_citrusy",
        "aroma_fruity",
        "aroma_floral",
        "aroma_herbal",
        "aroma_spicy",
        "aroma_resinous",
        "aroma_sugarlike",
        "aroma_misc",
        "aroma_descriptors",
        "substitutes",
        "yield_min",
        "yield_max",
        "maturity",
        "wilt_disease",
        "downy_mildew",
        "powdery_mildew",
        "aphid",
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
        "descriptors" => "array",
        "lineage" => "array",
        "aroma_descriptors" => "array",
        "substitutes" => "array",
    ];
}
