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

use App\Utility\Flags\BasicFlags;

final class Version implements VersionInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $major;

    /**
     * @var int|null
     */
    private int|null $minor;

    /**
     * @var int|null
     */
    private int|null $patch;

    /**
     * @var string|null
     */
    private string|null $suffix;

    /**
     * @var string|null
     */
    private string|null $commit;

    /**
     * @param string      $name
     * @param int         $major
     * @param int|null    $minor
     * @param int|null    $patch
     * @param string|null $suffix
     * @param string|null $commit
     */
    public function __construct(string $name, int $major, int|null $minor = null, int|null $patch = null, string|null $suffix = null, string|null $commit = null)
    {
        $this
            ->setName($name)
            ->setMajor($major)
            ->setMinor($minor)
            ->setPatch($patch)
            ->setSuffix($suffix)
            ->setCommit($commit);
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
        return $this->major;
    }

    /**
     * @param int $major
     *
     * @return self
     */
    public function setMajor(int $major): self
    {
        $this->major = $major;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinor(): int|null
    {
        return $this->minor;
    }

    /**
     * @param int|null $minor
     *
     * @return self
     */
    public function setMinor(int|null $minor): self
    {
        $this->minor = $minor ?? 0;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPatch(): int|null
    {
        return $this->patch;
    }

    /**
     * @param int|null $patch
     *
     * @return self
     */
    public function setPatch(int|null $patch): self
    {
        $this->patch = $patch ?? 0;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSuffix(): string|null
    {
        return $this->suffix;
    }

    /**
     * @param string|null $suffix
     *
     * @return self
     */
    public function setSuffix(string|null $suffix): self
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSuffix(): bool
    {
        return null !== $this->suffix;
    }

    /**
     * @return string|null
     */
    public function getCommit(): string|null
    {
        return $this->commit;
    }

    /**
     * @param string|null $commit
     *
     * @return self
     */
    public function setCommit(string|null $commit): self
    {
        $this->commit = $commit;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCommit(): bool
    {
        return null !== $this->commit;
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function getVersion(int $options = self::VERSION_THREE | self::VERSION_SUFFIX): string
    {
        ($flags = new BasicFlags())->addFlag($options);

        $version = '';

        if ($flags->matchFlag(self::VERSION_NAME)) {
            $version .= sprintf('[%s] ', $this->getName());
        }

        if ($flags->matchAnyFlag(self::VERSION_THREE | self::VERSION_PATCH | self::VERSION_MINOR | self::VERSION_MAJOR)) {
            $version .= sprintf('%d', $this->getMajor());
        }

        if ($flags->matchAnyFlag(self::VERSION_THREE | self::VERSION_PATCH | self::VERSION_MINOR)) {
            $version .= sprintf('.%d', $this->getMinor());
        }

        if ($flags->matchAnyFlag(self::VERSION_THREE | self::VERSION_PATCH)) {
            $version .= sprintf('.%d', $this->getPatch());
        }

        if ($this->hasSuffix() && $flags->matchFlag(self::VERSION_SUFFIX)) {
            $version .= sprintf('-%s', $this->getSuffix());
        }

        if ($this->hasCommit() && $flags->matchFlag(self::VERSION_COMMIT)) {
            $version .= sprintf('@%s', $this->getCommit());
        }

        return trim($version);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getVersion(self::VERSION_THREE | self::VERSION_SUFFIX | self::VERSION_COMMIT);
    }
}
