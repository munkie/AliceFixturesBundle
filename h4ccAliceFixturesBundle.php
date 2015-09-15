<?php

/*
 * This file is part of the h4cc/AliceFixtureBundle package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\AliceFixturesBundle;

use h4cc\AliceFixturesBundle\DependencyInjection\Compiler\AliceMethodsCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class h4ccAliceFixturesBundle
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class h4ccAliceFixturesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AliceMethodsCompilerPass());
    }
}
