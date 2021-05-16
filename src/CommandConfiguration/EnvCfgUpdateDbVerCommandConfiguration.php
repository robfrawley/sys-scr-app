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
use App\Utility\Version\NullVersion;
use App\Utility\Version\Version;
use App\Utility\Version\VersionInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

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
                self::getInstalledDatabaseVersion()->getVersion(
                    Version::VERSION_NAME | Version::VERSION_THREE
                )
            ),
        ];
    }

    /**
     * @return string|null
     */
    public function getCommandDescText(): string | null
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getCommandHelpText(): string | null
    {
        return null;
    }

    public static function getInstalledDatabaseVersion(): VersionInterface
    {
        ($p = new Process(['apt', 'show', 'mariadb-server']))->run();

        preg_match(
            '/^Version: (?:(?<release>[\d]+):)?(?<major>[\d]{1,2})\.(?<minor>[\d]{1,2})\.(?<patch>[\d]{1,2})(?<platform>[^\n]+)$/miu',
            $p->getOutput(),
            $matches,
            PREG_UNMATCHED_AS_NULL
        );

        return false === $p->isSuccessful() ? new NullVersion('mariadb') : new Version(
            'mariadb',
            $matches['major'],
            $matches['minor'],
            $matches['patch'],
            sprintf('%s%s', $matches['release'] ?? '', $matches['platform'] ?? '')
        );
    }
}
