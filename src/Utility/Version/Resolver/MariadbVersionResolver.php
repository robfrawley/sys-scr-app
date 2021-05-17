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

class MariadbVersionResolver extends AbstractVersionResolver
{
    public function resolveVersionInstance(): VersionMutableInterface | VersionImmutableInterface | VersionNullableInterface
    {
        ($p = new Process(['apt', 'show', 'mariadb-server']))->run();

        return (null !== $matches = $this->searchCliVersionOutput($p->getOutput()))
            ? new VersionImmutable('mariadb', $matches['major'], $matches['minor'], $matches['patch'], sprintf('release:%d', $matches['release']), null, $matches['platform'])
            : new VersionNullable('mariadb');
    }

    private function searchCliVersionOutput(string $output): array | null
    {
        return 1 === preg_match('/^Version: (?:(?<release>[\d]+):)?(?<major>[\d]{1,2})\.(?<minor>[\d]{1,2})\.(?<patch>[\d]{1,2})(?<platform>[^\n]+)$/miu', $output, $matches, PREG_UNMATCHED_AS_NULL)
            ? $matches
            : null;
    }
}
