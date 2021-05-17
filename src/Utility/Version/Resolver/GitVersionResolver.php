<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Resolver;

use App\Utility\Version\Immutable\VersionImmutable;
use App\Utility\Version\Immutable\VersionImmutableInterface;
use App\Utility\Version\Mutable\VersionMutableInterface;
use App\Utility\Version\Nullable\VersionNullable;
use App\Utility\Version\Nullable\VersionNullableInterface;
use Symfony\Component\Process\Process;

class GitVersionResolver extends AbstractVersionResolver
{
    /**
     * @var string | null
     */
    private string $gitDirectoryPath;

    public function __construct(string | null $cacheKeyName, string | null $gitDirectoryPath = null)
    {
        parent::__construct($cacheKeyName, $this->gitDirectoryPath = $gitDirectoryPath ?? dirname(__DIR__, 3));
    }

    public function resolveVersionInstance(): VersionMutableInterface | VersionImmutableInterface | VersionNullableInterface
    {
        $process = (new Process(['git', 'describe', '--tags', '--long', '--always']))
            ->setWorkingDirectory($this->gitDirectoryPath);

        $process->run();

        return ($process->isSuccessful() && (null !== $version = $this->parseCliVersionOutput($process->getOutput())))
            ? $version
            : new VersionNullable('git');
    }

    private function parseCliVersionOutput(string $output): VersionMutableInterface | VersionImmutableInterface | VersionNullableInterface | null
    {
        return ((null !== $matches = $this->parseCliVersionOutputSemVar($output)) || (null !== $matches = $this->parseCliVersionOutputHashed($output)))
            ? new VersionImmutable('git', $matches['major'], $matches['minor'], $matches['patch'], sprintf('release:%d', $matches['release']), $matches['commit'])
            : null;
    }

    private function parseCliVersionOutputSemVar(string $output): array | null
    {
        return 1 === preg_match('/^(?<major>[\d]{1,2})\.(?<minor>[\d]{1,2})\.(?<patch>[\d]{1,2})(?:-(?<release>[\d]+))?(?:-(?<commit>[\w]+))?$/miu', $output, $matches, PREG_UNMATCHED_AS_NULL)
            ? $matches
            : null;
    }

    private function parseCliVersionOutputHashed(string $output): array | null
    {
        return null;
    }
}
