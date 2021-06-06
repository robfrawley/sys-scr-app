<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Number;

class SizeNumber
{
    public const FAMILIAR_SCALE = 'familiar';

    public const PEDANTIC_SCALE = 'pedantic';

    private const SCALE_LOG_BASE = [
        'familiar' => 1024,
        'pedantic' => 1000,
    ];

    private const SCALE_STR_UNIT = [
        'familiar' => ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        'pedantic' => ['B', 'kiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'],
    ];

    public string $format;

    public int $precision;

    public string $scale;

    protected int $bytes;

    public function __construct(int $bytes, int | null $precision = null, string | null $scale = null, string | null $format = null)
    {
        $this->setBytes($bytes);
        $this->setPrecision($precision);
        $this->setScale($scale);
        $this->setFormat($format);
    }

    public function __toString(): string
    {
        [$value, $units, $power] = $this->scaledValueUnitsAndPower();

        return sprintf($this->format, round($value, $this->precision), $units);
    }

    public function setBytes(int $bytes): self
    {
        if (0 > $bytes) {
            throw new \DomainException(sprintf('Bytes must be zero or greater (got %d).', $bytes));
        }

        $this->bytes = $bytes;

        return $this;
    }

    public function setPrecision(int | null $precision = null): self
    {
        $this->precision = $precision ?? 2;

        return $this;
    }

    public function setScale(string | null $scale = null): self
    {
        $this->scale = $scale ?? self::FAMILIAR_SCALE;

        return $this;
    }

    public function setFormat(string | null $format): self
    {
        $this->format = $format ?? '%g %s';

        return $this;
    }

    /**
     * @return int[]
     */
    public function scaledValueUnitsAndPower(): array
    {
        if (0 === $this->bytes) {
            return [0, 0];
        }

        $power = floor(log($this->bytes, self::SCALE_LOG_BASE[$this->scale]));
        $value = $this->bytes / (self::SCALE_LOG_BASE[$this->scale] ** $power);
        $units = $this->getUnits($power);

        return [$value, $units, $power];
    }

    private function getUnits($power): string
    {
        return self::SCALE_STR_UNIT[$this->scale][$power] ?? '';
    }
}
