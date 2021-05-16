<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version;

final class NullVersion implements VersionInterface
{
    private string $name;

    public function __construct(string $name = 'null')
    {
        $this->setName($name);
    }

    public function __toString(): string
    {
        return $this->getVersion(self::VERSION_THREE | self::VERSION_SUFFIX | self::VERSION_COMMIT);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMajor(): int
    {
        return '';
    }

    public function setMajor(int $major): self
    {
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinor(): int | null
    {
        return null;
    }

    /**
     * @param int|null $minor
     */
    public function setMinor(int | null $minor): self
    {
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPatch(): int | null
    {
        return null;
    }

    /**
     * @param int|null $patch
     */
    public function setPatch(int | null $patch): self
    {
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSuffix(): string | null
    {
        return null;
    }

    /**
     * @param string|null $suffix
     */
    public function setSuffix(string | null $suffix): self
    {
        return $this;
    }

    public function hasSuffix(): bool
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getCommit(): string | null
    {
        return null;
    }

    /**
     * @param string|null $commit
     */
    public function setCommit(string | null $commit): self
    {
        return $this;
    }

    public function hasCommit(): bool
    {
        return false;
    }

    public function getVersion(int $options = self::VERSION_THREE | self::VERSION_SUFFIX): string
    {
        return '';
    }
}
