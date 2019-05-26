<?php

namespace App\DataFixtures\Faker;

use Cocur\Slugify\Slugify;
use Faker\Provider\Base as BaseProvider;

class SluggerProvider extends BaseProvider
{
    /**
     * Return a stringify string.
     *
     * @param string $stringToConvert
     *
     * @return string|string[]|null
     */
    public static function slugify(string $stringToConvert)
    {
        $slugify = new Slugify();

        return $slugify->slugify($stringToConvert);
    }
}