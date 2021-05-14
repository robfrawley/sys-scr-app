<?php

/*
 * This file is part of the `src-run/src-run-web` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\CommandConfiguration;

use App\Command\AbstractCommand;
use App\Component\Console\Style\AppStyle;
use App\Utility\Assert\ClassAttributesAssertUtility;
use Closure;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractCommandConfiguration implements CommandConfigurationInterface
{
    /**
     * @var TranslatorInterface|LocaleAwareInterface
     */
    private TranslatorInterface|LocaleAwareInterface $translator;

    /**
     * @var AbstractCommand
     */
    private AbstractCommand $command;

    /**
     * @var bool|null
     */
    private bool $enabled;

    /**
     * {@inheritdoc}
     *
     * @param TranslatorInterface $translator
     */
    final public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    final public function setTranslator(TranslatorInterface|LocaleAwareInterface $translator): self
    {
        $this->translator = self::validateTranslatorIsLocaleAware($translator);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return TranslatorInterface|LocaleAwareInterface
     */
    final public function getTranslator(): TranslatorInterface|LocaleAwareInterface
    {
        return $this->translator;
    }

    /**
     * {@inheritdoc}
     *
     * @param bool $enabled
     *
     * @return CommandConfigurationInterface
     */
    final public function setEnabled(bool $enabled = true): CommandConfigurationInterface
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    final public function isEnabled(): bool
    {
        return true === $this->enabled;
    }

    /**
     * {@inheritdoc}
     *
     * @param AbstractCommand $command
     * @param bool            $enabled
     *
     * @return CommandConfigurationInterface
     */
    final public function setCommand(AbstractCommand $command, bool $enabled = true): CommandConfigurationInterface
    {
        $this->command = $command;
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return AbstractCommand
     */
    final public function getCommand(): AbstractCommand
    {
        return $this->command;
    }

    /**
     * @return InputDefinition
     */
    final public function getCommandDefinition(): InputDefinition
    {
        return $this->getCommand()->getDefinition();
    }

    /**
     * @return string
     */
    final public function getCommandReference(): string
    {
        try {
            $reflect = (new \ReflectionProperty(static::class, 'commandReference'));
            $reflect->setAccessible(true);
            $aliases = $reflect->getDeclaringClass()->getName() === static::class
                ? $reflect->getValue()
                : null;
        } catch (\ReflectionException $e) {
            $aliases = null;
            $failure = $e;
        } finally {
            if (null !== ($aliases ?? null)) {
                return $aliases;
            }
        }

        throw new \RuntimeException(sprintf(
            'Failed to determine command reference static property of "%s"...', static::class
        ), 0, $failure ?? null);
    }

    /**
     * {@inheritdoc}
     *
     * @return CommandConfigurationInterface
     */
    public function configure(): CommandConfigurationInterface
    {
        return $this
            ->configureCommandDefGlobalArgs()
            ->configureCommandDefGlobalOpts()
            ->configureCommandDefCustomArgs()
            ->configureCommandDefCustomOpts()
            ->configureCommandDescText()
            ->configureCommandHelpText()
            ->configureCommandAliasSet();
    }

    /**
     * @param InputInterface  $i
     * @param OutputInterface $o
     *
     * @return AppStyle
     */
    public function setUpExec(InputInterface $i, OutputInterface $o): AppStyle
    {
        return new AppStyle($i, $o);
    }

    /**
     * @return CommandConfigurationInterface
     */
    final protected function configureCommandDefGlobalArgs(): CommandConfigurationInterface
    {
        $this->getCommandDefinition()->addArguments(
            $this->getCommandDefGlobalArgs()
        );

        return $this;
    }

    /**
     * @return CommandConfigurationInterface
     */
    final protected function configureCommandDefGlobalOpts(): CommandConfigurationInterface
    {
        $this->getCommandDefinition()->addOptions(
            $this->getCommandDefGlobalOpts()
        );

        return $this;
    }

    /**
     * @return CommandConfigurationInterface
     */
    final protected function configureCommandDefCustomArgs(): CommandConfigurationInterface
    {
        $this->getCommandDefinition()->addArguments(
            $this->getCommandDefCustomArgs()
        );

        return $this;
    }

    /**
     * @return CommandConfigurationInterface
     */
   final protected function configureCommandDefCustomOpts(): CommandConfigurationInterface
   {
       $this->getCommandDefinition()->addOptions(
           $this->getCommandDefCustomOpts()
       );

       return $this;
   }

    /**
     * @return CommandConfigurationInterface
     */
    final protected function configureCommandDescText(): CommandConfigurationInterface
    {
        return $this->invokeIfStrArgNotNull(
            $this->getCommandDescText(), fn($desc) => $this->getCommand()->setDescription($desc)
        );
    }

    /**
     * @return CommandConfigurationInterface
     */
    final protected function configureCommandHelpText(): CommandConfigurationInterface
    {
        return $this->invokeIfStrArgNotNull(
            $this->getCommandHelpText(), fn($help) => $this->getCommand()->setHelp($help)
        );
    }

    /**
     * @return CommandConfigurationInterface
     */
    final protected function configureCommandAliasSet(): CommandConfigurationInterface
    {
        return $this->invokeIfStrArgNotNull(
            $this->getCommandAliasSet(), fn($keys) => $this->getCommand()->setAliases($keys)
        );
    }

    /**
     * @return InputArgument[]
     */
    final public function getCommandDefGlobalArgs(): array
    {
        return [];
    }

    /**
     * @return InputOption[]
     */
    final public function getCommandDefGlobalOpts(): array
    {
        return [
            new InputOption('local', 'L', InputOption::VALUE_REQUIRED, 'Translation locale to use for command output.', 'en_US'),
        ];
    }

    /**
     * @return string[]|null
     */
    final public function getCommandAliasSet(): array|null
    {
        try {
            $reflect = (new \ReflectionProperty(\get_class($this->getCommand()), 'aliasesList'));
            $reflect->setAccessible(true);
            $aliases = $reflect->getDeclaringClass()->getName() === $this->getCommandReference()
                ? $reflect->getValue()
                : null;
        } catch (\ReflectionException $e) {}

        return $aliases ?? null;
    }

    /**
     * @param string|string[]|null $v
     * @param Closure     $c
     *
     * @return $this
     */
    private function invokeIfStrArgNotNull(string|array|null $v, Closure $c): self
    {
        if (null !== $v && false === empty($v)) { $c($v); }

        return $this;
    }

    /**
     * Ensure the passed TranslatorInterface object is also an instance of LocaleAwareInterface.
     *
     * @param TranslatorInterface|LocaleAwareInterface $translator
     *
     * @return LocaleAwareInterface
     */
    private static function validateTranslatorIsLocaleAware(TranslatorInterface|LocaleAwareInterface $translator): LocaleAwareInterface {
        ClassAttributesAssertUtility::assertInstanceOf(
            $translator,
            LocaleAwareInterface::class,
            __CLASS__,
            '__construct',
            true
        );

        return $translator;
    }
}
