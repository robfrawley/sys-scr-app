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
use App\Utility\Version\Nullable\VersionNullable;
use App\Utility\Version\Nullable\VersionNullableInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Process\Process;

class GitVersionResolver extends AbstractVersionResolver
{
    private const GIT_VER_PROCESS_AS_TAG_WITH_HASH = ['git', 'describe', '--long', '--always', '--tags'];
    private const GIT_VER_PROCESS_AS_ALL_WITH_HASH = ['git', 'describe', '--long', '--always', '--all'];
    private const GIT_SCH_OUTPUTS_AS_TAG_WITH_HASH = '/^(?<major>[\d]{1,2})\.(?<minor>[\d]{1,2})\.(?<patch>[\d]{1,2})(?:-(?<release>[\d]+))?(?:-(?<commit>[\w]+))?$/miu';
    private const GIT_SCH_OUTPUTS_AS_ALL_WITH_HASH = '/^(?<release>[a-z]+\/[a-z]+(?:-[\d]+)?)(?:-(?<commit>[\w]+))?$/miu';

    private string $gitDirectoryPath;

    public function __construct(string | null $cacheKeyName, string | null $gitDirectoryPath = null)
    {
        parent::__construct($cacheKeyName, ($this->gitDirectoryPath = $gitDirectoryPath ?? dirname(__DIR__, 3)));
    }

    public function resolveVersionInstance(): VersionImmutableInterface | VersionNullableInterface
    {
        return
            $this->resolveVersionInstanceFromProcessCall(self::GIT_VER_PROCESS_AS_ALL_WITH_HASH, function ($process, $default) {
                return (null !== $version = self::parseGitVersionAsAllWithHashVersion($process->getOutput())) ? $version : $default;
            }) ??
            $this->resolveVersionInstanceFromProcessCall(self::GIT_VER_PROCESS_AS_TAG_WITH_HASH, function ($process, $default) {
                return (null !== $version = self::parseGitVersionAsTagWithHashVersion($process->getOutput())) ? $version : $default;
            }) ??
            $this->resolveVersionInstanceFromProcessCall(self::GIT_VER_PROCESS_AS_ALL_WITH_HASH, function ($process, $default) {
                return (null !== $version = self::parseGitVersionAsAllWithHashVersion($process->getOutput())) ? $version : $default;
            });
    }

    public function resolveVersionInstanceFromProcessCall(array $options, callable $parser): VersionImmutableInterface | VersionNullableInterface
    {
        $default = new VersionNullable('git');
        $process = new Process($options);
        $process
            ->setWorkingDirectory($this->gitDirectoryPath)
            ->run();

        return $process->isSuccessful() ? $parser($process, $default) : $default;
    }

    private static function parseGitVersionAsTagWithHashVersion(string $output): VersionImmutableInterface | null
    {
        return (null !== $matches = self::parseGitVersionAsTagWithHashComponents($output)) ? self::createVersionFromMatches($matches) : null;
    }

    private static function parseGitVersionAsAllWithHashVersion(string $output): VersionImmutableInterface | null
    {
        return (null !== $matches = self::parseGitVersionAsAllWithHashComponents($output)) ? self::createVersionFromMatches($matches) : null;
    }

    private static function parseGitVersionAsTagWithHashComponents(string $output): array | null
    {
        return self::searchGitVersionOutputs(self::GIT_SCH_OUTPUTS_AS_TAG_WITH_HASH, $output);
    }

    private static function parseGitVersionAsAllWithHashComponents(string $output): array | null
    {
        return self::searchGitVersionOutputs(self::GIT_SCH_OUTPUTS_AS_ALL_WITH_HASH, $output);
    }

    private static function searchGitVersionOutputs(string $pattern, string $output): array | null
    {
        return (1 === preg_match($pattern, $output, $matches, PREG_UNMATCHED_AS_NULL)) ? $matches : null;
    }

    #[Pure]
    private static function createVersionFromMatches(array $matches): VersionImmutableInterface
    {
        return new VersionImmutable(
            'git',
            $matches['major'] ?? 0,
            $matches['minor'] ?? 0,
            $matches['patch'] ?? 0,
            sprintf('release:%d', $matches['release']),
            $matches['commit']
        );
    }
}
