<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Options;

use Niko9911\Flags\Bits;

interface VersionOptionsInterface
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
}
