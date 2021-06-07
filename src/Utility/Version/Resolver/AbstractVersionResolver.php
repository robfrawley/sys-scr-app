<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Resolver;

use App\Utility\Version\Immutable\VersionImmutableInterface;
use App\Utility\Version\Mutable\VersionMutableInterface;
use App\Utility\Version\Nullable\VersionNullableInterface;

abstract class AbstractVersionResolver implements VersionResolverInterface
{
    /**
     * @var VersionMutableInterface[]|VersionImmutableInterface[]|VersionNullableInterface[]
     */
    private static array $cachedVersions = [];

    private string $cacheKeyName;

    private string $cacheKeyHash;

    public function __construct(string | null $cacheKeyName, string | null $cacheKeyMore = null)
    {
        $this->setCacheKeyName($cacheKeyName, $cacheKeyMore);
    }

    public function resolve(): VersionImmutableInterface
    {
        return $this->getCacheVersion() ?? $this->newCacheVersion($this->resolveVersionInstance());
    }

    public function hasCacheKeyName(): bool
    {
        return null !== $this->cacheKeyName;
    }

    public function getCacheKeyName(): string | null
    {
        return $this->cacheKeyName;
    }

    public function setCacheKeyName(string | null $cacheKeyName, string | null $cacheKeyMore = null): self
    {
        $this->cacheKeyName = self::sanitizeCacheKeyName($cacheKeyName, $cacheKeyMore);
        $this->cacheKeyHash = self::sanitizeCacheKeyHash($this->cacheKeyName);

        return $this;
    }

    abstract protected function resolveVersionInstance(): VersionMutableInterface | VersionImmutableInterface | VersionNullableInterface;

    protected static function sanitizeCacheKeyHash(string $cacheKeyName): string
    {
        return hash('sha256', $cacheKeyName);
    }

    protected static function sanitizeCacheKeyName(string | null $cacheKeyName, string | null $cacheKeyMore = null): string
    {
        return sprintf('%s/more-info:%s', $cacheKeyName ?? self::generateCacheKeyName(), $cacheKeyMore ?? 'default');
    }

    protected static function generateCacheKeyName(): string
    {
        $digits = 13;

        while (--$digits > 0) {
            try {
                return sprintf('name-rand:%012d', (string) random_int((int) str_repeat(1, $digits), (int) str_repeat(9, $digits)));
            } catch (\Exception $e) {
                continue;
            }
        }

        return (new \DateTime())->format(sprintf('%s.%s', 'U', 'u'));
    }

    private function hasCacheVersion(): bool
    {
        return $this->hasCacheKeyHash() && array_key_exists($this->getCacheKeyHash(), self::$cachedVersions);
    }

    private function getCacheVersion(): VersionImmutableInterface | null
    {
        return $this->hasCacheVersion() ? self::$cachedVersions[$this->getCacheKeyHash()] : null;
    }

    private function newCacheVersion(VersionMutableInterface | VersionImmutableInterface | VersionNullableInterface $ver): VersionMutableInterface | VersionImmutableInterface | VersionNullableInterface
    {
        return self::$cachedVersions[$this->getCacheKeyHash()] = $ver;
    }

    private function hasCacheKeyHash(): bool
    {
        return null !== $this->cacheKeyHash;
    }

    private function getCacheKeyHash(): string | null
    {
        return $this->cacheKeyHash;
    }
}
