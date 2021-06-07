<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\CommandConfiguration\EvolvDnaThemeDownloadCommandConfiguration;
use App\Component\Finder\FileInfo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class EvolvDnaThemeDownloadCommand extends AbstractCommand
{
    protected static $defaultName = 'evolv-dna:theme-dl';

    public function __construct(EvolvDnaThemeDownloadCommandConfiguration $configuration)
    {
        parent::__construct($configuration);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int | null
    {
        parent::execute($input, $output);

        return 0;
    }

    protected function executeBackup(InputInterface $input, OutputInterface $output): int | null
    {
        parent::execute($input, $output);

        $this->style()->info(sprintf(
            'Resolved database type and version: <fg=white;bg=blue;options=bold>%s</>.', $input->getOption('db-version')
        ));

        $files = $this->getEnvFileInfos();

        $this->style()->info(sprintf(
            'Located the following %d environment variable files:', count($files)
        ));

        $this->style()->listing($files);

        if (!$this->style()->style()->confirm('Continue and update the database version in the enumerated files?', true)) {
            $this->style()->style()->warning('Terminating script operations per user request...');

            return 0;
        }

        foreach ($files as $f) {
            $this->handleFile($f);
        }

        return 0;
    }

    private function handleFile(FileInfo $file): void
    {
        $this->style()->newLine();
        $this->style()->section(sprintf(
            'Reading contents of environment file %s (%s)', $file->getFilename(), $file->getSizeNumber()->__toString()
        ));

        if ((null === $match = $this->getEnvFileDbMatches($file)) || 3 !== count($match)) {
            $this->style()->warning(sprintf(
                'No database url entries found in file %s', $file->getFilename()
            ));

            return;
        }

        $contents = $original = $file->getContents();
        $contents = str_replace($match['match'], sprintf('%s%s', $match['start'], $this->input->getOption('db-version')), $contents);

        if ($contents === $original) {
            $this->style()->info(sprintf('No updates required for environment file %s', $file));

            return;
        }

        if (false === file_put_contents($file->getRealPath(), $contents, LOCK_EX)) {
            $this->style()->caution(sprintf('Failed to update environment file %s', $file));

            return;
        }

        $this->style()->info(sprintf('Update completed for environment file %s', $file));
    }

    private function getEnvFileDbMatches(FileInfo $file): array | null
    {
        if (1 !== preg_match('/^(?<start>DATABASE_URL=[a-z]+:\/\/.+?serverVersion=)(?<version>(?:[a-z]+-)?[\d]+\.[\d]+(?:\.[\d]+)?)/mi', $file->getContents(), $matches, PREG_UNMATCHED_AS_NULL)) {
            return null;
        }

        return [
            'match' => $matches[0],
            'start' => $matches['start'],
            'version' => $matches['version'],
        ];
    }

    /**
     * @return FileInfo[]
     */
    private function getEnvFileInfos(): array
    {
        return FileInfo::fromSplFileInfoArray(...$this->getEnvSplFileInfos());
    }

    /**
     * @return SplFileInfo[]
     */
    private function getEnvSplFileInfos(): array
    {
        return $this->resolveEnvFiles($this->input->getArgument('environment-file')) ?: $this->locateEnvFiles();
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
            ->files()
    ;

        return $finder->hasResults() ? iterator_to_array($finder) : [];
    }
}
