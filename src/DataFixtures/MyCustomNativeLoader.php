<?php

namespace App\DataFixtures;

use App\DataFixtures\Faker\SluggerProvider;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Faker\Factory as FakerGeneratorFactory;
use Faker\Generator as FakerGenerator;

//ajout du provider custom

class MyCustomNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = FakerGeneratorFactory::create(parent::LOCALE);
        $generator->addProvider(new AliceProvider());
        $generator->addProvider(new SluggerProvider($generator));

        $generator->seed($this->getSeed());

        return $generator;
    }
}
