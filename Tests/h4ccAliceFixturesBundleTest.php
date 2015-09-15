<?php

/*
 * This file is part of the h4cc/AliceFixtureBundle package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\AliceFixturesBundle\Tests;

use h4cc\AliceFixturesBundle\h4ccAliceFixturesBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class h4ccAliceFixturesBundleTest
 *
 * @author Julius Beckmann <github@h4cc.de>
 * @covers h4cc\AliceFixturesBundle\h4ccAliceFixturesBundle
 */
class h4ccAliceFixturesBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        /** @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject $containerMock */
        $containerMock = $this->getMockBuilder('\Symfony\Component\DependencyInjection\ContainerBuilder')
          ->setMethods(array('addCompilerPass'))
          ->disableOriginalConstructor()
          ->getMock();

        $containerMock
            ->expects($this->exactly(2))
            ->method('addCompilerPass')
            ->withConsecutive(
                [$this->isInstanceOf('\h4cc\AliceFixturesBundle\DependencyInjection\Compiler\ProcessorCompilerPass')],
                [$this->isInstanceOf('\h4cc\AliceFixturesBundle\DependencyInjection\Compiler\AliceMethodsCompilerPass')]
            );

        $bundle = new h4ccAliceFixturesBundle();
        $bundle->build($containerMock);
    }
}
