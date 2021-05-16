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

use App\Command\EnvCfgUpdateDbVerCommand;
use App\Utility\Version\Version;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

class EnvCfgUpdateDbVerCommandConfiguration extends AbstractCommandConfiguration
{
    /**
     * @var string
     */
    protected static string $commandReference = EnvCfgUpdateDbVerCommand::class;

    /**
     * @return InputArgument[]
     */
    public function getCommandDefCustomArgs(): array
    {
        return [
            new InputArgument(
                'env-file',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Paths to the environment files to update.',
                []
            ),
        ];
    }

    /**
     * @return InputOption[]
     */
    public function getCommandDefCustomOpts(): array
    {
        return [
            new InputOption(
                'db-version',
                'd',
                InputOption::VALUE_OPTIONAL,
                'The database version to set in the environment files.',
                (string) self::getInstalledDatabaseVersion()
            ),
        ];
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

    /**
     * @return Version
     */
    public static function getInstalledDatabaseVersion(): Version
    {
        $p = new Process(['mysql', '--version']);
        $p->run();

        if (!$p->isSuccessful()) {

        }

        return new Version(0, 0, 0);
    }
}
