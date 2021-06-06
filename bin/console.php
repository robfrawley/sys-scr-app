<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use App\Kernel;
use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

if (!\in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true)) {
    throw new \RuntimeException(sprintf('The console must be invoked via the CLI version of PHP, not the %s SAPI.', PHP_SAPI));
}

\set_time_limit(0);

require \dirname(__DIR__) . '/vendor/autoload.php';

if (!\class_exists(Application::class) || !\class_exists(Dotenv::class)) {
    throw new LogicException('You need to add "symfony/framework-bundle" and "symfony/dotenv" as Composer dependencies.');
}

$input = new ArgvInput();

if (null !== $env = $input->getParameterOption(['--env', '-e'], null, true)) {
    \putenv('APP_ENV=' . $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = $env);
}

if ($input->hasParameterOption('--no-debug', true)) {
    \putenv('APP_DEBUG=' . $_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = '0');
}

(new Dotenv())->bootEnv(
    \dirname(__DIR__) . '/.env'
);

if (($_SERVER['APP_DEBUG'] ?? false) && \class_exists(Debug::class)) {
    \umask(0000);
    Debug::enable();
}

try {
    return (new Application(
        new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG'], $suluContext ?? SuluKernel::CONTEXT_ADMIN)
    ))->run($input);
} catch (\Exception $exception) {
    throw new \RuntimeException(sprintf('Failed to run application successfully; exited with exception: "%s".', $exception->getMessage()), $exception->getCode(), $exception);
}
