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
    public const VERSION_MAJOR = Bits::BIT_1;

    public const VERSION_MINOR = Bits::BIT_2;

    public const VERSION_PATCH = Bits::BIT_3;

    public const VERSION_THREE = Bits::BIT_4;

    public const VERSION_SUFFIX = Bits::BIT_5;

    public const VERSION_COMMIT = Bits::BIT_6;

    public const VERSION_NAME = Bits::BIT_7;
}
