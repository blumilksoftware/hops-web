<?php

declare(strict_types=1);

namespace Tests\Helpers;

class HopFixture
{
    public static function cascade(): string
    {
        return <<<'JSON5'
{
  id: "cascade",
  name: "Cascade",
  altName: null,
  country: "US",
  descriptors: ["citrusy", "fruity", "herbal"],
  origin: "A very popular aroma hop.",
  aroma: {
    citrusy: 3,
    fruity: 3,
    floral: 1,
    herbal: 3,
    spicy: 0,
    resinous: 1,
    sugarlike: 0,
    misc: 0
  },
  aromaDescription: ["lime", "black currant"],
  agronomic: {
    yield: { min: 1600, max: 2200 },
    maturity: "early to mid early"
  },
  ingredients: {
    alphas: { min: 4.5, max: 7.0 },
    betas: { min: 4.5, max: 7.0 },
    cohumulones: { min: 33, max: 40 },
    polyphenols: null,
    xanthohumols: { min: 0.1, max: 0.4 },
    oils: { min: 0.8, max: 1.5 },
    farnesenes: { min: 4.0, max: 8.0 },
    linalool: { min: 0.4, max: 0.6 },
    thiols: "high",
    alternatives: {
      brewhouse: ["centennial", "lemondrop"],
      dryhopping: ["centennial", "lemondrop"]
    }
  }
}
JSON5;
    }

    public static function minimal(): string
    {
        return <<<'JSON5'
{
  name: "Test Hop",
  country: "DE",
  aroma: {
    citrusy: 1,
    fruity: 0,
    floral: 0,
    herbal: 0,
    spicy: 0,
    resinous: 0,
    sugarlike: 0,
    misc: 0
  },
  aromaDescription: [],
  ingredients: {
    alphas: { min: 5.0, max: 8.0 },
    betas: null,
    cohumulones: null,
    polyphenols: null,
    xanthohumols: null,
    oils: null,
    farnesenes: null,
    linalool: null,
    alternatives: {
      brewhouse: [],
      dryhopping: []
    }
  }
}
JSON5;
    }
}
