<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

if (file_exists(dirname(__DIR__) . '/var/cache/admin/prod/App_KernelProdContainer.preload.php')) {
    require dirname(__DIR__) . '/var/cache/admin/prod/App_KernelProdContainer.preload.php';
}

if (file_exists(dirname(__DIR__) . '/var/cache/website/prod/App_KernelProdContainer.preload.php')) {
    require dirname(__DIR__) . '/var/cache/website/prod/App_KernelProdContainer.preload.php';
}
