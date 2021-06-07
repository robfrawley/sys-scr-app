<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Number;

class ImmutableNumber
{
    protected float $number;

    protected string | null $unit;

    public function __construct(float | int $number, string $unit = null)
    {
        $this->number = (float) $number;
        $this->unit = $unit;
    }

    public function __toString(): string
    {
        return $this->stringify();
    }

    public function stringify(int $precision = null): string
    {
        $number = (string) ($precision ? round($this->number, $precision) : $this->number);

        if ($this->hasUnit()) {
            $number .= sprintf(' %s', $this->unit);
        }

        return $number;
    }

    public function getNumber(bool $asFloat = true): float | int
    {
        return $asFloat ? $this->getNumberAsFloat() : $this->getNumberAsInteger();
    }

    public function getNumberAsFloat(): float
    {
        return $this->number;
    }

    public function getNumberAsInteger(): int
    {
        return (int) $this->number;
    }

    public function hasUnit(): bool
    {
        return null !== $this->unit;
    }

    public function getUnit(): string | null
    {
        return $this->unit;
    }
}
