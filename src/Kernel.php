<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App;

use FOS\HttpCache\SymfonyCache\HttpCacheProvider;
use Sulu\Bundle\HttpCacheBundle\Cache\SuluHttpCache;
use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Kernel extends SuluKernel implements HttpCacheProvider
{
    private HttpKernelInterface | null $httpCache;

    public function getHttpCache(): HttpKernelInterface | SuluHttpCache | null
    {
        if (!$this->httpCache) {
            $this->httpCache = new SuluHttpCache($this);
            // Activate the following for user based caching see also:
            // https://foshttpcachebundle.readthedocs.io/en/latest/features/user-context.html
            //
            //$this->httpCache->addSubscriber(
            //    new \FOS\HttpCache\SymfonyCache\UserContextListener([
            //        'session_name_prefix' => 'SULUSESSID',
            //    ])
            //);
        }

        return $this->httpCache;
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('container.dumper.inline_class_loader', true);

        parent::configureContainer($container, $loader);
    }
}
