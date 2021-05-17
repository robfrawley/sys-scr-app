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

use App\CommandConfiguration\EnvCfgUpdateDbVerCommandConfiguration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class EnvCfgUpdateDbVerCommand extends AbstractCommand
{
    /**
     * Contains the static name of this command, which is used as the index to call the command from the cli console command.
     *
     * @var string
     */
    protected static $defaultName = 'env-cfg:update:db-ver';

    /**
     * Contains set of aliases that the command can also be called using.
     *
     * @var string[]
     */
    protected static array $aliasesList = [
        'sr:env:update:db-ver',
    ];

    public function __construct(EnvCfgUpdateDbVerCommandConfiguration $configuration)
    {
        parent::__construct($configuration);
    }

    /**
     * @throws \ReflectionException
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): int | null
    {
        parent::execute($input, $output);

        $this->style()->note(sprintf(
            'Resolved database type and version to be "%s".', $input->getOption('db-version')
        ));

        $files = $this->resolveEnvFiles($input->getArgument('environment-file')) ?: $this->locateEnvFiles();

        $this->style()->note(sprintf(
            'Applying database version update to "%d" environment files:', count($files)
        ));

        $this->style()->listing($files);

        return 0;
    }

    /**
     * @param string[] $files
     *
     * @return string[]
     */
    private function resolveEnvFiles(array $files): array
    {
        return array_filter(array_map(static fn (string $file) => realpath($file), $files));
    }

    /**
     * @return string[]
     */
    private function locateEnvFiles(): array
    {
        ($finder = new Finder())
            ->in(dirname(__DIR__, 2))
            ->name('.env*')
            ->depth(0)
            ->ignoreDotFiles(false)
            ->ignoreVCS(true)
            ->files();

        return $finder->hasResults() ? iterator_to_array($finder) : [];
    }
}
