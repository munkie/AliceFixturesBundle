<?php

/*
 * This file is part of the h4cc/AliceFixtureBundle package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\AliceFixturesBundle\ORM\SchemaTool;

/**
 * Class SchemaToolInterface
 *
 * Schema tool with basic schema operations.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
interface SchemaToolInterface
{
    /**
     * Recreate create & drop or just purge data
     */
    public function recreateSchema();

}
