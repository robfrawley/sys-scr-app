<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Symfony\Component\Dotenv\Dotenv;

require \dirname(__DIR__) . '/vendor/autoload.php';
(new Dotenv())->bootEnv(\dirname(__DIR__) . '/.env');
