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

    public function __toString(): string;

    public function getName(): string;

    public function setName(string $name): self;

    public function getMajor(): int;

    public function setMajor(int $major): self;

    /**
     * @return int|null
     */
    public function getMinor(): int | null;

    /**
     * @param int|null $minor
     */
    public function setMinor(int | null $minor): self;

    /**
     * @return int|null
     */
    public function getPatch(): int | null;

    /**
     * @param int|null $patch
     */
    public function setPatch(int | null $patch): self;

    /**
     * @return string|null
     */
    public function getSuffix(): string | null;

    /**
     * @param string|null $suffix
     */
    public function setSuffix(string | null $suffix): self;

    public function hasSuffix(): bool;

    /**
     * @return string|null
     */
    public function getCommit(): string | null;

    /**
     * @param string|null $commit
     */
    public function setCommit(string | null $commit): self;

    public function hasCommit(): bool;

    public function getVersion(int $options = self::VERSION_THREE | self::VERSION_SUFFIX): string;
}
