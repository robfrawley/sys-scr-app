<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Assert;

use JetBrains\PhpStorm\Pure;

/**
 * Class ClassAttributesAssertUtility
 *
 * @package App\Utility\Assert
 */
class ClassAttributesAssertUtility
{
    /**
     * ClassAttributesAssertUtility constructor throws exception as this is a static-method-only class.
     */
    public function __construct()
    {
        throw new \RuntimeException(\sprintf(
            'Class "%s" cannot be instantiated as it only contains static methods.', __CLASS__
        ));
    }

    /**
     * Assert provided object is an instance of the expected class/trait/interface.
     *
     * @param object             $provided
     * @param string|object      $expected
     * @param string|object|null $callingObjsName
     * @param string|null        $callingFuncName
     * @param bool               $throwOnFailure
     *
     * @return bool
     */
    public static function assertInstanceOf(object $provided, string|object $expected, string|object|null $callingObjsName = null, string|null $callingFuncName = null, bool $throwOnFailure = false): bool
    {
        if ($provided instanceof (self::toObjectName($expected))) {
            return true;
        }

        if (false === $throwOnFailure) {
            return false;
        }

        throw new \LogicException(\sprintf(
            'The "%s" object (passed into %s context) must be an instance of "%s" but an instance of "%s" was provided instead.',
            self::toObjectName($provided),
            self::getCallerCtx($callingObjsName, $callingFuncName),
            self::toObjectName($expected),
            self::toObjectName($provided)
        ));
    }

    /**
     * Get calling context string description if object and function names are not null.
     *
     * @param string|object|null $callingObjsName
     * @param string|null        $callingFuncName
     *
     * @return string
     */
    private static function getCallerCtx(string|object|null $callingObjsName = null, string|null $callingFuncName = null): string
    {
        if (!\empty($callingObjsName) && !\empty($callingFuncName)) {
            return \sprintf('"%s::%s()"', self::toObjectName($callingObjsName), $callingFuncName);
        }

        if (!\empty($callingObjsName) && \empty($callingFuncName)) {
            return \sprintf('"%s"', self::toObjectName($callingObjsName));
        }

        return  'an unknown';
    }

    /**
     * Get the object name as a string, regardless of whether the object is passed as a string or an instance.
     *
     * @param string|object $object
     *
     * @return string
     */
    private static function toObjectName(string|object $object): string
    {
        return \is_object($object) ? \get_class($object) : $object;
    }
}
