<?php

/*
 * This file is part of the h4cc/AliceFixtureBundle package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\AliceFixturesBundle\Command;

use h4cc\AliceFixturesBundle\FixtureManagerRegistry;
use h4cc\AliceFixturesBundle\Fixtures\FixtureSet;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadFilesCommand
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class LoadFilesCommand extends ContainerAwareCommand
{
    /**
     * @var FixtureManagerRegistry
     */
    protected $fixtureManagerRegistry;

    protected function configure()
    {
        $this
          ->setName('h4cc_alice_fixtures:load:files')
          ->setDescription('Load fixture files using alice and faker.')
          ->addArgument('files', InputArgument::IS_ARRAY, 'List of files to import.')
          ->addOption('seed', null, InputOption::VALUE_OPTIONAL, 'Seed for random generator.')
          ->addOption('locale', 'l', InputOption::VALUE_OPTIONAL, 'Locale for Faker provider.')
          ->addOption('no-persist', 'np', InputOption::VALUE_NONE, 'Persist loaded entities in database.')
          ->addOption('drop', 'd', InputOption::VALUE_NONE, 'Drop and create Schema before loading.')
          ->addOption('manager', 'm', InputOption::VALUE_OPTIONAL, 'The fixture manager name to used.', 'default');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('manager');
        $files = $input->getArgument('files');

        if (!$files) {
            $output->writeln('No files to load');
        }

        $manager = $this->fixtureManagerRegistry->getManager($name);

        $fixtureSet = $manager->createFixtureSet();
        $fixtureSet->addFiles($files);

        $this->populateSet($fixtureSet, $input);

        $manager->load($fixtureSet);
    }

    /**
     * @param FixtureSet $fixtureSet
     * @param InputInterface $input
     */
    protected function populateSet(FixtureSet $fixtureSet, InputInterface $input)
    {
        $seed = $input->getOption('seed');
        $locale = $input->getOption('locale');
        $persist = !$input->getOption('no-persist');
        $drop = $input->getOption('drop');

        if ($seed) {
            $fixtureSet->setSeed($seed);
        }
        if ($locale) {
            $fixtureSet->setLocale($locale);
        }
        if ($persist) {
            $fixtureSet->setDoPersist($persist);
        }
        if ($drop) {
            $fixtureSet->setDoDrop($drop);
        }
    }
}
