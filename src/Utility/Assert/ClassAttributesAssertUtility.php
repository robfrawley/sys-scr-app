<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Assert;

class ClassAttributesAssertUtility
{
    public function __construct()
    {
        throw new \RuntimeException(\sprintf('Class "%s" cannot be instantiated as it only contains static methods.', __CLASS__));
    }

    public static function assertInstanceOf(object $provided, string | object $expected, string | object | null $callingObjsName = null, string | null $callingFuncName = null, bool $throwOnFailure = false): object | null
    {
        if (false === $throwOnFailure) {
            return ($provided instanceof (self::toObjectName($expected))) ? $provided : (false === $throwOnFailure ? null : (throw new \LogicException()));
        }

        throw new \LogicException(\sprintf('The "%s" object (passed into %s context) must be an instance of "%s" but an instance of "%s" was provided instead.', self::toObjectName($provided), self::getCallerCtx($callingObjsName, $callingFuncName), self::toObjectName($expected), self::toObjectName($provided)));
    }

    private static function getCallerCtx(string | object | null $callingObjsName = null, string | null $callingFuncName = null): string
    {
        if (!\empty($callingObjsName) && !\empty($callingFuncName)) {
            return \sprintf('"%s::%s()"', self::toObjectName($callingObjsName), $callingFuncName);
        }

        if (!\empty($callingObjsName) && \empty($callingFuncName)) {
            return \sprintf('"%s"', self::toObjectName($callingObjsName));
        }

        return 'an unknown';
    }

    private static function toObjectName(string | object $object): string
    {
        return \is_object($object) ? \get_class($object) : $object;
    }
}
