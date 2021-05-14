<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\CommandConfiguration\UpdateEnvDbVerCommandConfiguration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateEnvDbVerCommand extends AbstractCommand
{
    /**
     * Contains the static name of this command, which is used as the index to call the command from the cli console command.
     *
     * @var string
     */
    protected static $defaultName = 'sr:env-cfg:db-ver:update';

    /**
     * Contains set of aliases that the command can also be called using.
     *
     * @var string[]
     */
    protected static array $aliasesList = [];

    /**
     * @param UpdateEnvDbVerCommandConfiguration $configuration
     */
    public function __construct(UpdateEnvDbVerCommandConfiguration $configuration)
    {
        parent::__construct($configuration);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): int|null
    {
        parent::execute($input, $output);

        return 0;
    }
}
