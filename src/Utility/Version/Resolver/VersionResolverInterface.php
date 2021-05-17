<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Resolver;

use App\Utility\Version\Immutable\VersionImmutableInterface;

interface VersionResolverInterface
{
    public function resolve(): VersionImmutableInterface;
}
