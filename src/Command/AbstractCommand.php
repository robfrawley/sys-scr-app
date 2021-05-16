<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\CommandConfiguration\CommandConfigurationInterface;
use App\Component\Console\Style\AppStyle;
use App\Utility\Version\GitVersion;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractCommand extends Command
{
    /**
     * Contains the default static name prefix for all application commands.
     *
     * @var string
     */
    protected static string $defaultPref = 'src-run';

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
     * @return string|null
     *
     * @throws ReflectionException
     */
    #[Pure] public static function getDefaultPref(): string|null
    {
        return property_exists(static::class, 'defaultPref') ? static::$defaultPref : null;
    }

    /**
     * @return string|null
     *
     * @throws ReflectionException
     */
    #[Pure] public static function getDefaultName(): string|null
    {
        return property_exists(static::class, 'defaultName') ? static::getDefaultPref().':'.static::$defaultName : null;
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
        return (new GitVersion('0.0.1'))->getVersion();
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
     * @throws ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int|null
    {
        $this->appStyle = $this->configuration->setUpExec($input, $output);
        $this->style()->title(sprintf(
            'Application Command: "%s" (%s)', self::getDefaultName(), self::getVersion()
        ));

        return null;
    }
}
