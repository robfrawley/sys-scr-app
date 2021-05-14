<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
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
