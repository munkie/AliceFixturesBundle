<?php

/*
 * This file is part of the h4cc/AliceFixtureBundle package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\AliceFixturesBundle\Loader;

use Nelmio\Alice\Fixtures\Loader;

/**
 * Class Factory
 * Factory for loaders.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class Factory implements FactoryInterface
{
    /**
     * Faker providers.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Processors.
     *
     * @var \Nelmio\Alice\Instances\Processor\Methods\MethodInterface[]
     */
    protected $processors = [];

    /**
     * Builders
     *
     * @var \Nelmio\Alice\Fixtures\Builder\Methods\MethodInterface[]
     */
    protected $builders = [];

    /**
     * Instantiators
     *
     * @var \Nelmio\Alice\Instances\Instantiator\Methods\MethodInterface[]
     */
    protected $instantiators = [];

    /**
     * Parsers
     *
     * @var \Nelmio\Alice\Fixtures\Parser\Methods\MethodInterface[]
     */
    protected $parsers = [];

    /**
     * Populators
     *
     * @var \Nelmio\Alice\Instances\Populator\Methods\MethodInterface[]
     */
    protected $populators = [];

    /**
     * Factory constructor.
     *
     * @param array $providers
     * @param \Nelmio\Alice\Instances\Processor\Methods\MethodInterface[] $processors
     * @param \Nelmio\Alice\Fixtures\Builder\Methods\MethodInterface[] $builders
     * @param \Nelmio\Alice\Instances\Instantiator\Methods\MethodInterface[] $instantiators
     * @param \Nelmio\Alice\Fixtures\Parser\Methods\MethodInterface[] $parsers
     * @param \Nelmio\Alice\Instances\Populator\Methods\MethodInterface[] $populators
     */
    public function __construct(
        array $providers = [],
        array $processors = [],
        array $builders = [],
        array $instantiators = [],
        array $parsers = [],
        array $populators = []
    ) {
        $this->providers = $providers;
        $this->processors = $processors;
        $this->builders = $builders;
        $this->instantiators = $instantiators;
        $this->parsers = $parsers;
        $this->populators = $populators;
    }

    /**
     * Returns a loader for a specific type and locale.
     *
     * @param string $locale
     * @return Loader
     */
    public function getLoader($locale)
    {
        $loader = new Loader($locale);

        foreach ($this->builders as $builder) {
            $loader->addBuilder($builder);
        }
        foreach ($this->instantiators as $instantiator) {
            $loader->addInstantiator($instantiator);
        }
        foreach ($this->parsers as $parser) {
            $loader->addParser($parser);
        }
        foreach ($this->populators as $populator) {
            $loader->addPopulator($populator);
        }
        foreach ($this->processors as $processor) {
            $loader->addProcessor($processor);
        }
        foreach ($this->providers as $provider) {
            $loader->addProvider($provider);
        }

        return $loader;
    }
}
