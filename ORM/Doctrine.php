<?php

/*
 * This file is part of the h4cc/AliceFixtureBundle package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\AliceFixturesBundle\ORM;


use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class Doctrine
 *
 * Adding more ORM actions.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class Doctrine implements ORMInterface
{
    /**
     * @var bool
     */
    protected $flush;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    // We need to collect all the used managers for flushing them.
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager[]|\SplObjectStorage
     */
    protected $managersToFlush;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param bool $doFlush
     */
    public function __construct(ManagerRegistry $managerRegistry, $doFlush = true)
    {
        $this->flush = $doFlush;
        $this->managerRegistry = $managerRegistry;

        $this->managersToFlush = new \SplObjectStorage();
    }

    /**
     * {@inheritDoc}
     */
    public function persist(array $objects)
    {
        foreach ($objects as $object) {
            $manager = $this->getManagerForObject($object);
            $this->managersToFlush->attach($manager);

            $manager->persist($object);
        }

        $this->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function find($class, $id)
    {
        $entity = $this->getManagerForClass($class)->find($class, $id);

        if (null === $entity) {
            throw new \UnexpectedValueException(
                sprintf('Entity with Id %s and Class %s not found', $id, $class)
            );
        }

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(array $objects)
    {
        $objects = $this->merge($objects);

        foreach ($objects as $object) {
            $manager = $this->getManagerForObject($object);
            $this->managersToFlush->attach($manager);

            $manager->remove($object);
        }

        $this->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function merge(array $objects)
    {
        $mergedObjects = array();

        foreach ($objects as $object) {
            $mergedObjects[] = $this->getManagerForObject($object)->merge($object);
        }

        return $mergedObjects;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(array $objects)
    {
        foreach ($objects as $object) {
            $this->getManagerForObject($object)->detach($object);
        }
    }

    /**
     * @param object $object
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function getManagerForObject($object)
    {
        return $this->getManagerForClass(get_class($object));
    }

    /**
     * @param string $class
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \RuntimeException
     */
    private function getManagerForClass($class)
    {
        $manager = $this->managerRegistry->getManagerForClass($class);

        if (null === $manager) {
            throw new \RuntimeException(sprintf('No ObjectManager for class %s', $class));
        }

        return $manager;
    }

    private function flush()
    {
        if ($this->flush) {
            foreach ($this->managersToFlush as $manager) {
                $manager->flush();
            }
        }

        // Calling a static method in a not static way.
        $this->managersToFlush->removeAll($this->managersToFlush);
    }
}
 