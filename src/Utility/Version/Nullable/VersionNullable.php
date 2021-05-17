<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Nullable;

use App\Utility\Version\Immutable\VersionImmutable;

class VersionNullable extends VersionImmutable implements VersionNullableInterface
{
    public function __construct(string | null $name = null)
    {
        parent::__construct($name);
    }
}
