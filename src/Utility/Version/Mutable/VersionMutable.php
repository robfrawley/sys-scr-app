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

use App\Utility\Version\Immutable\VersionImmutable;

class VersionMutable extends VersionImmutable implements VersionMutableInterface
{
    public function setName(string $name): VersionMutableInterface
    {
        $this->name = $name;

        return $this;
    }

    public function setMajor(int $major): VersionMutableInterface
    {
        $this->major = $major;

        return $this;
    }

    public function setMinor(int | null $minor): VersionMutableInterface
    {
        $this->minor = $minor ?? 0;

        return $this;
    }

    public function setPatch(int | null $patch): VersionMutableInterface
    {
        $this->patch = $patch ?? 0;

        return $this;
    }

    public function setSuffix(string | null $suffix): VersionMutableInterface
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function setCommit(string | null $commit): VersionMutableInterface
    {
        $this->commit = $commit;

        return $this;
    }

    public function setExtras(string ...$extras): VersionMutableInterface
    {
        $this->extras = $extras;

        return $this;
    }

    public function addExtras(string ...$extras): VersionMutableInterface
    {
        array_push($this->extras, ...$extras);

        return $this;
    }

    public function delExtras(): VersionMutableInterface
    {
        $this->extras = [];

        return $this;
    }
}
