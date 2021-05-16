<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run> (MIT License)
 * (c) Sebastian Bergmann <sebastian@phpunit.de> (BSD-3-Clause)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version;

final class NullVersion implements VersionInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name = 'null')
    {
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getMajor(): int
    {
        return '';
    }

    /**
     * @param int $major
     *
     * @return self
     */
    public function setMajor(int $major): self
    {
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinor(): int|null
    {
        return null;
    }

    /**
     * @param int|null $minor
     *
     * @return self
     */
    public function setMinor(int|null $minor): self
    {
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPatch(): int|null
    {
        return null;
    }

    /**
     * @param int|null $patch
     *
     * @return self
     */
    public function setPatch(int|null $patch): self
    {
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSuffix(): string|null
    {
        return null;
    }

    /**
     * @param string|null $suffix
     *
     * @return self
     */
    public function setSuffix(string|null $suffix): self
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSuffix(): bool
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getCommit(): string|null
    {
        return null;
    }

    /**
     * @param string|null $commit
     *
     * @return self
     */
    public function setCommit(string|null $commit): self
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function hasCommit(): bool
    {
        return false;
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function getVersion(int $options = self::VERSION_THREE | self::VERSION_SUFFIX): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getVersion(self::VERSION_THREE | self::VERSION_SUFFIX | self::VERSION_COMMIT);
    }
}
