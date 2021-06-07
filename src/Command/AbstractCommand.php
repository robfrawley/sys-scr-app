<?php

/*
 * This file is part of the `src-run/sys-scr-app` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\CommandConfiguration\CommandConfigurationInterface;
use App\Component\Console\Style\AppStyle;
use App\Component\Console\Style\AppStyleWrapper;
use App\Utility\Version\Options\VersionOptionsInterface;
use App\Utility\Version\Resolver\GitVersionResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractCommand extends Command
{
    protected static string $defaultPref = 'src-run';

    protected static array $aliasesList = [];

    protected CommandConfigurationInterface $configuration;

    protected TranslatorInterface | LocaleAwareInterface $localeAwTrans;

    protected AppStyleWrapper | null $style;

    protected InputInterface $input;

    protected OutputInterface $output;

    public function __construct(CommandConfigurationInterface $configuration)
    {
        $this->configuration = $configuration->setCommand($this, true);
        $this->localeAwTrans = $configuration->getTranslator();

        parent::__construct();
    }

    public static function getDefaultPref(): string | null
    {
        return property_exists(static::class, 'defaultPref') ? static::$defaultPref : null;
    }

    public static function getDefaultName(): string | null
    {
        return property_exists(static::class, 'defaultName') ? static::getDefaultPref() . ':' . static::$defaultName : null;
    }

    public function isEnabled(): bool
    {
        return $this->configuration->isEnabled();
    }

    public static function getVersion(): string
    {
        return (new GitVersionResolver('project-level-git'))
            ->resolve()
            ->getVersion(VersionOptionsInterface::VERSION_THREE | VersionOptionsInterface::VERSION_COMMIT)
        ;
    }

    public function style(): AppStyleWrapper
    {
        if ($this->style instanceof AppStyleWrapper) {
            return $this->style;
        }

        throw new \RuntimeException(sprintf('Command property "style" for "%s" has not yet been assigned an instance of "%s"...', static::class, AppStyle::class));
    }

    protected function configure(): void
    {
        $this->configuration->configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int | null
    {
        $this->input = $input;
        $this->output = $output;
        $this->style = $this->configuration->setUpExec($input, $output);
        $this->style()->title(sprintf(
            'App Command => ["%s" (v%s)]', self::getDefaultName(), self::getVersion()
        ));

        return null;
    }
}
