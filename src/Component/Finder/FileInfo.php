<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Component\Finder;

use App\Utility\Number\SizeNumber;
use Symfony\Component\Finder\SplFileInfo;

class FileInfo extends SplFileInfo
{
    public static function fromSplFileInfo(SplFileInfo $fileInfo): self
    {
        return new self($fileInfo->getFilename(), $fileInfo->getRelativePath(), $fileInfo->getRelativePathname());
    }

    /**
     * @return SplFileInfo[]
     */
    public static function fromSplFileInfoArray(SplFileInfo ...$fileInfos): array
    {
        return array_map(static fn (SplFileInfo $fileInfo) => self::fromSplFileInfo($fileInfo), $fileInfos);
    }

    public function getSizeNumber(): SizeNumber
    {
        return new SizeNumber($this->getSize());
    }
}
