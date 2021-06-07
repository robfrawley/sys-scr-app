<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Immutable;

use App\Utility\Version\Options\VersionOptionsInterface;

interface VersionImmutableInterface extends VersionOptionsInterface
{
    public function __toString(): string;

    public function getVersion(int $options = self::VERSION_THREE | self::VERSION_SUFFIX): string;

    public function getName(): string | null;

    public function hasName(): bool;

    public function getMajor(): int | null;

    public function hasMajor(): bool;

    public function getMinor(): int | null;

    public function hasMinor(): bool;

    public function getPatch(): int | null;

    public function hasPatch(): bool;

    public function getSuffix(): string | null;

    public function hasSuffix(): bool;

    public function getCommit(): string | null;

    public function hasCommit(): bool;

    public function getExtras(): array;

    public function hasExtras(): bool;

    public function numExtras(): int;
}
