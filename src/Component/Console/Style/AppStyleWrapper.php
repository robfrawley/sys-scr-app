<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Component\Console\Style;

class AppStyleWrapper
{
    public function __construct(
        private AppStyle $style
    ) {
    }

    public function __call(string $name, array $args): self
    {
        if (!method_exists($this->style, $name)) {
            throw new \RuntimeException($this->compileString('Method %s::%s() does not exist!', get_class($this->style), $name));
        }

        $this->style->{$name}($this->compileString(array_shift($args), ...$args));

        return $this;
    }

    public function title($format, mixed ...$replace): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileString($format, ...$replace));
    }

    public function section($format, mixed ...$replace): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileString($format, ...$replace));
    }

    public function listing(array $lines): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $lines);
    }

    public function listingA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function textA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function text($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function commentA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function comment($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function successA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function success($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function errorA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function error($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function warningA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function warning($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function noteA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function note($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function infoA(array $instructions): self
    {
        $this->autoPrependBlock();
        $this->writeln($this->createBlock($this->compileArrays($instructions), 'INFO', 'fg=white;bg=blue', ' ', true));
        $this->newLine();

        return $this;
    }

    public function info($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function cautionA(array $instructions): self
    {
        return $this->invokeStyleFunction(__FUNCTION__, $this->compileArrays($instructions));
    }

    public function caution($format, mixed ...$replace): self
    {
        return $this->invokeMyOwnFunction(__FUNCTION__, $format, ...$replace);
    }

    public function autoPrependBlock(): void
    {
        $this->callPrivateStyleMethod(__FUNCTION__);
    }

    public function newLine(): void
    {
        $this->callPrivateStyleMethod(__FUNCTION__);
    }

    public function writeln($messages, int $type = AppStyle::OUTPUT_NORMAL): void
    {
        $this->callPrivateStyleMethod(__FUNCTION__, $messages, $type);
    }

    public function style(): AppStyle
    {
        return $this->style;
    }

    private static function stringToArrays($format, mixed ...$replace): array
    {
        return [[$format, ...$replace]];
    }

    private function createBlock(iterable $messages, string $type = null, string $style = null, string $prefix = ' ', bool $padding = false, bool $escape = false): array
    {
        return $this->callPrivateStyleMethod(__FUNCTION__, $messages, $type, $style, $prefix, $padding, $escape);
    }

    private function invokeMyOwnFunction(string $name, mixed ...$arguments): self
    {
        $name .= 'A';

        $this->{$name}(self::stringToArrays(...$arguments));

        return $this;
    }

    private function invokeStyleFunction(string $name, mixed ...$parameters): self
    {
        if ('A' === $name[-1]) {
            $name = mb_substr($name, 0, -1);
        }

        if (!is_callable([$this->style(), $name])) {
            throw new \RuntimeException(sprintf('Method %s::%s() cannot be called from %s::%s()!', get_class($this->style()), $name, static::class, __FUNCTION__));
        }

        $this->style()->{$name}(...$parameters);

        return $this;
    }

    private function callPrivateStyleMethod(string $name, mixed ...$args): mixed
    {
        try {
            $m = (new \ReflectionObject($this->style))->getMethod($name);
            $m->setAccessible(true);

            return $m->invokeArgs($this->style, $args);
        } catch (\Exception $e) {
            throw new \RuntimeException($this->compileString('Could not call private method %s::%s()!', get_class($this->style), $name));
        }
    }

    private function compile(string | array $values): string | array
    {
        return is_array($values) ? $this->compileArrays($values) : $this->compileString($values);
    }

    private function compileString(string | array $format, mixed ...$arguments): string | array
    {
        return is_array($format) ? array_map(static fn (string $f) => sprintf($f, ...$arguments), $format) : sprintf($format, ...$arguments);
    }

    private function compileArrays(array $instructions): array
    {
        return array_map(static fn (array $inst) => sprintf(...$inst), $instructions);
    }
}
