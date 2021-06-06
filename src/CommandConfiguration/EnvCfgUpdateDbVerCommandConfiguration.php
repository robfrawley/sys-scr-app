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
use App\Utility\Version\Immutable\VersionImmutableInterface;
use App\Utility\Version\Mutable\VersionMutable;
use App\Utility\Version\Mutable\VersionMutableInterface;
use App\Utility\Version\Nullable\VersionNullableInterface;
use App\Utility\Version\Resolver\MariadbVersionResolver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class EnvCfgUpdateDbVerCommandConfiguration extends AbstractCommandConfiguration
{
    protected static string $commandReference = EnvCfgUpdateDbVerCommand::class;

    /**
     * @return InputArgument[]
     */
    public function getCommandDefCustomArgs(): array
    {
        return [
            new InputArgument(
                'environment-file',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Paths to the environment files to update.',
                null
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
                self::getInstalledDatabaseVersion()->getVersion(
                    VersionMutable::VERSION_NAME | VersionMutable::VERSION_THREE
                )
            ),
        ];
    }

    public function getCommandDescText(): string | null
    {
        return null;
    }

    public function getCommandHelpText(): string | null
    {
        return null;
    }

    public static function getInstalledDatabaseVersion(): VersionMutableInterface | VersionImmutableInterface | VersionNullableInterface
    {
        return (new MariadbVersionResolver('system-level-mariadb'))->resolve();
    }
}
