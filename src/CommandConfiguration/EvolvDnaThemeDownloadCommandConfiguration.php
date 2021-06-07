<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\CommandConfiguration;

use App\Command\EvolvDnaThemeDownloadCommand;
use App\Utility\Version\Immutable\VersionImmutableInterface;
use App\Utility\Version\Mutable\VersionMutableInterface;
use App\Utility\Version\Nullable\VersionNullableInterface;
use App\Utility\Version\Resolver\MariadbVersionResolver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class EvolvDnaThemeDownloadCommandConfiguration extends AbstractCommandConfiguration
{
    protected static string $commandReference = EvolvDnaThemeDownloadCommand::class;

    /**
     * @return InputArgument[]
     */
    public function getCommandDefCustomArgs(): array
    {
        return [
            new InputArgument(
                'links',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Links to theme pages on forum.evolvapor.com website.',
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
                'output-path',
                'o',
                InputOption::VALUE_OPTIONAL,
                'The output directory path to write downloaded theme files to.',
                getcwd()
            ),
            new InputOption(
                'all-versions',
                'a',
                InputOption::VALUE_NONE,
                'The output directory path to write downloaded theme files to.',
                getcwd()
            ),
        ];
    }

    public function getCommandDescText(): string | null
    {
        return 'Downloads Evolv DNA theme files (.ecigtheme) from the forum.evolvapor.com website forums.';
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
