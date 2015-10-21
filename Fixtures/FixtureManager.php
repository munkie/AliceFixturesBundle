<?php

/*
 * This file is part of the h4cc/AliceFixtureBundle package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\AliceFixturesBundle\Fixtures;

use h4cc\AliceFixturesBundle\Loader\FactoryInterface;
use h4cc\AliceFixturesBundle\ORM\ORMInterface;
use h4cc\AliceFixturesBundle\ORM\SchemaTool\SchemaToolInterface;
use Nelmio\Alice\Fixtures;
use Nelmio\Alice\ProcessorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FixtureManager
 * Manager for fixture files and also fixture sets.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class FixtureManager implements FixtureManagerInterface
{
    /**
     * Pre\PostPersist processors.
     *
     * @var ProcessorInterface[]
     */
    protected $processors = [];

    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Default options for new FixtureSets.
     *
     * @var array
     */
    protected $options = [];

    /**
     * @var ORMInterface
     */
    protected $persister;

    /**
     * @var SchemaToolInterface
     */
    protected $schemaTool;

    /**
     * FixtureManager constructor.
     * @param array $options
     * @param ORMInterface $persister
     * @param FactoryInterface $loaderFactory
     * @param SchemaToolInterface $schemaTool
     * @param LoggerInterface $logger
     */
    public function __construct(
        array $options,
        ORMInterface $persister,
        FactoryInterface $loaderFactory,
        SchemaToolInterface $schemaTool,
        LoggerInterface $logger
    )
    {
        $this->options = array_merge(
            $this->getDefaultOptions(),
            $options
        );
        $this->persister = $persister;
        $this->loaderFactory = $loaderFactory;
        $this->schemaTool = $schemaTool;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'seed' => 1,
            'locale' => 'en_EN',
        );
    }

    /**
     * Loads entities from file, does _not_ persist them.
     *
     * @param array $files
     * @return array
     */
    public function loadFiles(array $files, $doPersist = false, $doDrop = false)
    {
        $set = $this->createFixtureSet();
        foreach ($files as $file) {
            $set->addFile($file);
        }
        $set->setDoPersist($doPersist);
        $set->setDoDrop($doDrop);

        return $this->load($set);
    }

    /**
     * Returns a new configured fixture set.
     *
     * @return FixtureSet
     */
    public function createFixtureSet()
    {
        return new FixtureSet($this->options);
    }

    /**
     * {@inheritDoc}
     */
    public function load(FixtureSet $set)
    {
        $loader = $this->loaderFactory->getLoader($set->getLocale());

        // Objects are the loaded entities without "local".
        $objects = [];

        // Load each file
        foreach ($set->getFiles() as $dataOrFilename) {
            // Use seed before each loading, so results will be more predictable.
            $this->initSeedFromSet($set);

            $dataName = is_array($dataOrFilename) ? 'data' : 'file ' . $dataOrFilename;

            $this->logger->debug(sprintf('Loading %s ...', $dataName));
            $newObjects = $loader->load($dataOrFilename['path']);

            $this->logger->debug(sprintf('Loaded %d object(s) from %s.', count($newObjects), $dataName));
            $objects = array_merge($objects, $newObjects);
        }

        if ($set->getDoPersist()) {
            $this->persist($objects, $set->getDoDrop());
        }

        return $objects;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(array $objects, $drop = false)
    {
        if ($drop) {
            $this->logger->debug('Recreating schema ...');
            $this->schemaTool->recreateSchema();
            $this->logger->debug('Recreated schema.');
        }

        $this->logger->debug(sprintf('Persisting %d loaded objects ...', count($objects)));
        $this->persistObjects($this->persister, $objects);
        $this->logger->debug(sprintf('Persisted %d loaded objects.', count($objects)));
    }

    /**
     * {@inheritDoc}
     */
    public function remove(array $entities)
    {
        $this->persister->remove($entities);
    }

    /**
     * Returns global options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Adds a processor for processing a entity before and after persisting.
     *
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
        $this->logger->debug('Added processor: ' . get_class($processor));
    }

    /**
     * Initializes the seed for random numbers, given by a fixture set.
     *
     * @param FixtureSet $set
     */
    protected function initSeedFromSet(FixtureSet $set)
    {
        if (is_numeric($set->getSeed())) {
            mt_srand($set->getSeed());
            $this->logger->debug('Initialized with seed ' . $set->getSeed());
        } else {
            mt_srand();
            $this->logger->debug('Initialized with random seed');
        }
    }

    /**
     * Persists given objects using ORM persister, and calls registered processors.
     *
     * @param ORMInterface $persister
     * @param $objects
     */
    protected function persistObjects(ORMInterface $persister, array $objects)
    {
        foreach ($this->processors as $processor) {
            foreach ($objects as $obj) {
                $processor->preProcess($obj);
            }
        }

        $persister->persist($objects);

        foreach ($this->processors as $processor) {
            foreach ($objects as $obj) {
                $processor->postProcess($obj);
            }
        }
    }
}
