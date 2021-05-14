<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run> (MIT License)
 * (c) Sebastian Bergmann <sebastian@phpunit.de> (BSD-3-Clause)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Utility\Version;

final class GitVersion
{
    /**
     * @var string
     */
    private string $release;

    /**
     * @var string
     */
    private string $gitPath;

    /**
     * @var string
     */
    private string $verPref;

    /**
     * @var string|null
     */
    private string|null $keyPath;

    /**
     * @var string[]
     */
    private static array $version = [];

    /**
     * @param string      $release
     * @param string|null $gitPath
     * @param string|null $verPref
     */
    public function __construct(string $release, string|null $gitPath = null, string|null $verPref = 'version ')
    {
        $this->release = $release;
        $this->gitPath = $gitPath ?? dirname(__DIR__, 3);
        $this->verPref = $verPref ?? '';
    }

    /**
     * @param bool $useVerPref
     *
     * @return string
     */
    public function getVersion(bool $useVerPref = true): string
    {
        if ($this->hasVersionForInstance() === false) {
            $this->setVersionForInstance($this->resolveVersionString());
        }

        return $this->getVersionForInstance($useVerPref);
    }

    /**
     * @return bool
     */
    private function hasVersionForInstance(): bool
    {
        return isset(self::$version[$this->getPathKey()]) && !empty(self::$version[$this->getPathKey()]);
    }

    /**
     * @param string $version
     */
    private function setVersionForInstance(string $version): void
    {
        self::$version[$this->getPathKey()] = $version;
    }

    /**
     * @param bool $useVerPref
     *
     * @return string
     */
    private function getVersionForInstance(bool $useVerPref): string
    {
        return (($useVerPref ? $this->verPref : '').self::$version[$this->getPathKey()]) ?: 'n/a';
    }

    /**
     * @return string
     */
    private function resolveVersionString(): string
    {
        $version = null;

        if (\substr_count($this->release, '.') + 1 === 3) {
            $version = $this->release;
        } else {
            $version = $this->release.'-dev';
        }

        if (null !== ($git = $this->getGitInformation())) {
            if (\substr_count($this->release, '.') + 1 === 3) {
                $version = $git;
            } else {
                $git = \explode('-', $git);

                $version = $this->release.'-'.\end($git);
            }
        }

        return $version;
    }

    /**
     * @return string|null
     */
    private function getGitInformation(): string|null
    {
        if (!\is_dir($this->gitPath.DIRECTORY_SEPARATOR.'.git')) { return null; }

        $process = \proc_open('git describe --tags --long --always', [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes, $this->gitPath);

        if (\is_resource($process)) {
            $result = \trim(\stream_get_contents($pipes[1]));

            \fclose($pipes[1]);
            \fclose($pipes[2]);
        }

        return (!\is_resource($process) || 0 !== \proc_close($process))
            ? null
            : $result;
    }

    /**
     * @return string
     */
    private function getPathKey(): string
    {
        return $this->keyPath ?? ($this->keyPath = hash('sha1', $this->gitPath));
    }
}
