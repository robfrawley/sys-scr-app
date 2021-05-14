<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\CommandConfiguration;

use App\Command\UpdateEnvDbVerCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class UpdateEnvDbVerCommandConfiguration extends AbstractCommandConfiguration
{
    /**
     * @var string
     */
    protected static string $commandReference = UpdateEnvDbVerCommand::class;

    /**
     * @return InputArgument[]
     */
    public function getCommandDefCustomArgs(): array
    {
        return [];
    }

    /**
     * @return InputOption[]
     */
    public function getCommandDefCustomOpts(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getCommandDescText(): string|null
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getCommandHelpText(): string|null
    {
        return null;
    }
}
