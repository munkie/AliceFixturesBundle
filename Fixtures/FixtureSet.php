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
use h4cc\AliceFixturesBundle\FixtureOptionsResolver;

/**
 * Class FixtureSet
 * Set of files and options for import with FixtureManager.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class FixtureSet implements FixtureSetInterface
{
    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var array
     */
    protected $options;

    /**
     * See getDefaultOptions() for possible options.
     *
     * @param array $options
     */
    public function __construct()
    {
    }

    /**
     * Adds a file to the set.
     *
     * @param array|string $dataOrFilename
     */
    public function addFile($dataOrFilename)
    {
        $this->files[] = $dataOrFilename;
        return $this;
    }

    /**
     * @param array $files
     */
    public function addFiles(array $files)
    {
        foreach ($files as $file) {
            $this->addFile($file);
        }
    }

    /**
     * Returns a list of file paths and types.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return boolean
     */
    public function getDoDrop()
    {
        return $this->options['do_drop'];
    }

    /**
     * @param boolean $doDrop
     */
    public function setDoDrop($doDrop)
    {
        $this->options['do_drop'] = (boolean)$doDrop;
    }

    /**
     * @return boolean
     */
    public function getDoPersist()
    {
        return $this->options['do_persist'];
    }

    /**
     * @param boolean $doPersist
     */
    public function setDoPersist($doPersist)
    {
        $this->options['do_persist'] = (boolean)$doPersist;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->options['locale'];
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->options['locale'] = $locale;
    }

    /**
     * @return int|null
     */
    public function getSeed()
    {
        return $this->options['seed'];
    }

    /**
     * @param int $seed
     */
    public function setSeed($seed)
    {
        $this->options['seed'] = is_null($seed) ? null : (integer)$seed;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->options['order'];
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->options['order'] = is_null($order) ? 1 : (integer)$order;
    }
}
