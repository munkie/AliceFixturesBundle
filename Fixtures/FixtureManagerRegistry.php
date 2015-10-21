<?php
namespace h4cc\AliceFixturesBundle;

use h4cc\AliceFixturesBundle\Fixtures\FixtureManagerInterface;

class FixtureManagerRegistry
{
    /**
     * @var FixtureManagerInterface[]
     */
    protected $managers = [];

    /**
     * @param $name
     * @param FixtureManagerInterface $manager
     */
    public function addManager($name, FixtureManagerInterface $manager)
    {
        $this->managers[$name] = $manager;
    }

    /**
     * @param string $name
     * @return FixtureManagerInterface
     */
    public function getManager($name)
    {
        return $this->managers[$name];
    }
}
