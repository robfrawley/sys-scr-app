<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version\Immutable;

use App\Utility\Flags\BasicFlags;

class VersionImmutable implements VersionImmutableInterface
{
    protected array $extras;

    public function __construct(
        protected string | null $name,
        protected int | null $major = null,
        protected int | null $minor = null,
        protected int | null $patch = null,
        protected string | null $suffix = null,
        protected string | null $commit = null,
        string ...$extras
    ) {
        $this->extras = $extras;
    }

    public function __toString(): string
    {
        return $this->getVersion(self::VERSION_THREE | self::VERSION_SUFFIX | self::VERSION_COMMIT);
    }

    public function getVersion(int $options = self::VERSION_THREE | self::VERSION_SUFFIX): string
    {
        ($flags = new BasicFlags())->addFlag($options);

        $version = '';

        if ($flags->matchAnyFlag(self::VERSION_NAME) && $this->hasName()) {
            $version .= sprintf('%s-', $this->getName());
        }

        if ($flags->matchAnyFlag(self::VERSION_THREE | self::VERSION_PATCH | self::VERSION_MINOR | self::VERSION_MAJOR) && $this->hasMajor()) {
            $version .= sprintf('%d', $this->getMajor());
        }

        if ($flags->matchAnyFlag(self::VERSION_THREE | self::VERSION_PATCH | self::VERSION_MINOR) && $this->hasMinor() && $this->hasMajor()) {
            $version .= sprintf('.%d', $this->getMinor());
        }

        if ($flags->matchAnyFlag(self::VERSION_THREE | self::VERSION_PATCH) && $this->hasPatch() && $this->hasMinor() && $this->hasMajor()) {
            $version .= sprintf('.%d', $this->getPatch());
        }

        if ($flags->matchAnyFlag(self::VERSION_SUFFIX) && $this->hasSuffix()) {
            $version .= sprintf('-%s', $this->getSuffix());
        }

        if ($flags->matchAnyFlag(self::VERSION_COMMIT) && $this->hasCommit()) {
            $version .= sprintf('@%s', $this->getCommit());
        }

        return trim($version);
    }

    public function getName(): string | null
    {
        return $this->name;
    }

    public function hasName(): bool
    {
        return null !== $this->name;
    }

    public function getMajor(): int | null
    {
        return $this->major;
    }

    public function hasMajor(): bool
    {
        return null !== $this->major;
    }

    public function getMinor(): int | null
    {
        return $this->minor;
    }

    public function hasMinor(): bool
    {
        return null !== $this->minor;
    }

    public function getPatch(): int | null
    {
        return $this->patch;
    }

    public function hasPatch(): bool
    {
        return null !== $this->patch;
    }

    public function getSuffix(): string | null
    {
        return $this->suffix;
    }

    public function hasSuffix(): bool
    {
        return null !== $this->suffix;
    }

    public function getCommit(): string | null
    {
        return $this->commit;
    }

    public function hasCommit(): bool
    {
        return null !== $this->commit;
    }

    public function getExtras(): array
    {
        return $this->extras;
    }

    public function hasExtras(): bool
    {
        return false === empty($this->extras);
    }

    public function numExtras(): int
    {
        return count($this->extras);
    }
}
