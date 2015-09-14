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
use Nelmio\Alice\PersisterInterface;

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
     * Processors.
     *
     * @var \Nelmio\Alice\Instances\Processor\Methods\MethodInterface[]
     */
    protected $processors = [];

    /**
     * Optional logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Persister
     *
     * @var PersisterInterface
     */
    protected $persister;

    /**
     * Returns a loader for a specific type and locale.
     *
     * @param $locale
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

        $loader->setPersister($this->persister);
        if ($this->logger) {
            $loader->setLogger($this->logger);
        }

        return $loader;
    }
}
