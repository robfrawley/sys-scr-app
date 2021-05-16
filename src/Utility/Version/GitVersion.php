<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version;

final class GitVersion
{
    private string $release;

    private string $gitPath;

    private string $verPref;

    /**
     * @var string|null
     */
    private string | null $keyPath;

    /**
     * @var string[]
     */
    private static array $version = [];

    /**
     * @param string|null $verPref
     */
    public function __construct(string $release, string | null $gitPath = null, string | null $verPref = 'version ')
    {
        $this->release = $release;
        $this->gitPath = $gitPath ?? dirname(__DIR__, 3);
        $this->verPref = $verPref ?? '';
    }

    public function getVersion(bool $useVerPref = true): string
    {
        if (false === $this->hasVersionForInstance()) {
            $this->setVersionForInstance($this->resolveVersionString());
        }

        return $this->getVersionForInstance($useVerPref);
    }

    private function hasVersionForInstance(): bool
    {
        return isset(self::$version[$this->getPathKey()]) && !empty(self::$version[$this->getPathKey()]);
    }

    private function setVersionForInstance(string $version): void
    {
        self::$version[$this->getPathKey()] = $version;
    }

    private function getVersionForInstance(bool $useVerPref): string
    {
        return (($useVerPref ? $this->verPref : '') . self::$version[$this->getPathKey()]) ?: 'n/a';
    }

    private function resolveVersionString(): string
    {
        $version = null;

        if (3 === \mb_substr_count($this->release, '.') + 1) {
            $version = $this->release;
        } else {
            $version = $this->release . '-dev';
        }

        if (null !== ($git = $this->getGitInformation())) {
            if (3 === \mb_substr_count($this->release, '.') + 1) {
                $version = $git;
            } else {
                $git = \explode('-', $git);

                $version = $this->release . '-' . \end($git);
            }
        }

        return $version;
    }

    /**
     * @return string|null
     */
    private function getGitInformation(): string | null
    {
        if (!\is_dir($this->gitPath . DIRECTORY_SEPARATOR . '.git')) {
            return null;
        }

        $process = \proc_open('git describe --tags --long --always', [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes, $this->gitPath);

        if (\is_resource($process)) {
            $result = \trim(\stream_get_contents($pipes[1]));

            \fclose($pipes[1]);
            \fclose($pipes[2]);
        }

        return (\is_resource($process) && 0 === \proc_close($process)) ? $result : null;
    }

    private function getPathKey(): string
    {
        return $this->keyPath ?? ($this->keyPath = \hash('sha1', $this->gitPath));
    }
}
