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

use Niko9911\Flags\Bits;

interface VersionInterface
{
    /**
     * Includes the major version integer in the version output (ex: "name <x>.x.x[-suffix[@commit]]")
     *
     * @var int
     */
    public const VERSION_MAJOR = Bits::BIT_1;

    /**
     * Includes the minor version integer in the version output (ex: "name x.<x>.x[-suffix[@commit]]")
     *
     * @var int
     */
    public const VERSION_MINOR = Bits::BIT_2;

    /**
     * Includes the patch version integer in the version output (ex: "name x.x.<x>[-suffix[@commit]]")
     *
     * @var int
     */
    public const VERSION_PATCH = Bits::BIT_3;

    /**
     * Includes the patch version integer in the version output (ex: "name x.x.<x>[-suffix[@commit]]")
     *
     * @var int
     */
    public const VERSION_THREE = Bits::BIT_4;

    /**
     * Adds the suffix after the version integers (if available) (ex: "name x.x.x[-<suffix>[@commit]]")
     *
     * @var int
     */
    public const VERSION_SUFFIX = Bits::BIT_5;

    /**
     * Adds the commit hash at the end of the version string (if available) (ex: "name x.x.x[-suffix[@<commit>]]")
     *
     * @var int
     */
    public const VERSION_COMMIT = Bits::BIT_6;

    /**
     * Prefixes the version string with the name property (ex: "<name> x.x.x[-suffix[@commit]]")
     *
     * @var int
     */
    public const VERSION_NAME = Bits::BIT_7;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self;

    /**
     * @return int
     */
    public function getMajor(): int;

    /**
     * @param int $major
     *
     * @return self
     */
    public function setMajor(int $major): self;

    /**
     * @return int|null
     */
    public function getMinor(): int|null;

    /**
     * @param int|null $minor
     *
     * @return self
     */
    public function setMinor(int|null $minor): self;

    /**
     * @return int|null
     */
    public function getPatch(): int|null;

    /**
     * @param int|null $patch
     *
     * @return self
     */
    public function setPatch(int|null $patch): self;

    /**
     * @return string|null
     */
    public function getSuffix(): string|null;

    /**
     * @param string|null $suffix
     *
     * @return self
     */
    public function setSuffix(string|null $suffix): self;

    /**
     * @return bool
     */
    public function hasSuffix(): bool;

    /**
     * @return string|null
     */
    public function getCommit(): string|null;

    /**
     * @param string|null $commit
     *
     * @return self
     */
    public function setCommit(string|null $commit): self;

    /**
     * @return bool
     */
    public function hasCommit(): bool;

    /**
     * @param int $options
     *
     * @return string
     */
    public function getVersion(int $options = self::VERSION_THREE | self::VERSION_SUFFIX): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
