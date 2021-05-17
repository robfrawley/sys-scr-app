<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Mutable;

use App\Utility\Version\Immutable\VersionImmutableInterface;

interface VersionMutableInterface extends VersionImmutableInterface
{
    public function setName(string $name): self;

    public function setMajor(int $major): self;

    public function setMinor(int | null $minor): self;

    public function setPatch(int | null $patch): self;

    public function setSuffix(string | null $suffix): self;

    public function setCommit(string | null $commit): self;

    public function setExtras(string ...$extras): self;

    public function addExtras(string ...$extras): self;

    public function delExtras(): self;
}
