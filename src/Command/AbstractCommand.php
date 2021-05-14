<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\CommandConfiguration\CommandConfigurationInterface;
use App\Component\Console\Style\AppStyle;
use SebastianBergmann\Version;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractCommand extends Command
{
    /**
     * Handles setting up the command configuration options, like arguments, options, description, help text, etc.
     *
     * @var CommandConfigurationInterface
     */
    protected CommandConfigurationInterface $configuration;

    /**
     * @var TranslatorInterface|LocaleAwareInterface
     */
    protected TranslatorInterface|LocaleAwareInterface $localeAwTrans;

    /**
     * @var AppStyle|null
     */
    protected AppStyle|null $appStyle;

    /**
     * AbstractCommand constructor.
     *
     * @param CommandConfigurationInterface $configuration
     */
    public function __construct(CommandConfigurationInterface $configuration)
    {
        $this->configuration = $configuration->setCommand($this, true);
        $this->localeAwTrans = $configuration->getTranslator();

        parent::__construct();
    }

    /**
     * Checks whether the command is enabled or not in the current environment.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->configuration->isEnabled();
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return (new Version('0.0.1', __DIR__))->getVersion();
    }

    /**
     * @return AppStyle
     */
    public function style(): AppStyle
    {
        if ($this->appStyle instanceof AppStyle) {
            return $this->appStyle;
        }

        throw new \RuntimeException(sprintf(
            'Command property "appStyle" for "%s" has not yet been assigned an instance of "%s"...', static::class, AppStyle::class
        ));
    }

    /**
     * Configure command arguments, options, description, help text, and other command attributes.
     */
    protected function configure(): void
    {
        $this->configuration->configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): int|null
    {
        $this->appStyle = $this->configuration->setUpExec($input, $output);
        $this->style()->title(sprintf(
            'Running Application Command: "%s" (%s)', self::getDefaultName(), self::getVersion()
        ));

        return null;
    }
}
