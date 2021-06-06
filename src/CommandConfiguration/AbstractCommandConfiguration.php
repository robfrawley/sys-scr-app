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
use App\Component\Console\Style\AppStyleWrapper;
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
    final public function __construct(
        public TranslatorInterface $translator,
        private bool | null $enabled = null,
        private AbstractCommand | null $command = null,
    ) {
    }

    final public function getTranslator(): TranslatorInterface | LocaleAwareInterface
    {
        return $this->translator;
    }

    final public function setEnabled(bool $enabled = true): CommandConfigurationInterface
    {
        $this->enabled = $enabled;

        return $this;
    }

    final public function isEnabled(): bool
    {
        return true === $this->enabled;
    }

    final public function setCommand(AbstractCommand $command, bool $enabled = true): CommandConfigurationInterface
    {
        $this->command = $command;
        $this->enabled = $enabled;

        return $this;
    }

    final public function getCommand(): AbstractCommand
    {
        return $this->command;
    }

    final public function getCommandDefinition(): InputDefinition
    {
        return $this->getCommand()->getDefinition();
    }

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

        throw new \RuntimeException(sprintf('Failed to determine command reference static property of "%s"...', static::class), 0, $failure ?? null);
    }

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

    public function setUpExec(InputInterface $i, OutputInterface $o): AppStyleWrapper
    {
        return new AppStyleWrapper(new AppStyle($i, $o));
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
    final public function getCommandAliasSet(): array | null
    {
        try {
            $reflect = (new \ReflectionProperty(\get_class($this->getCommand()), 'aliasesList'));
            $reflect->setAccessible(true);
            $aliases = $reflect->getDeclaringClass()->getName() === $this->getCommandReference()
                ? $reflect->getValue()
                : null;
        } catch (\ReflectionException $e) {
        }

        return $aliases ?? null;
    }

    final protected function configureCommandDefGlobalArgs(): CommandConfigurationInterface
    {
        $this->getCommandDefinition()->addArguments(
            $this->getCommandDefGlobalArgs()
        );

        return $this;
    }

    final protected function configureCommandDefGlobalOpts(): CommandConfigurationInterface
    {
        $this->getCommandDefinition()->addOptions(
            $this->getCommandDefGlobalOpts()
        );

        return $this;
    }

    final protected function configureCommandDefCustomArgs(): CommandConfigurationInterface
    {
        $this->getCommandDefinition()->addArguments(
            $this->getCommandDefCustomArgs()
        );

        return $this;
    }

    final protected function configureCommandDefCustomOpts(): CommandConfigurationInterface
    {
        $this->getCommandDefinition()->addOptions(
           $this->getCommandDefCustomOpts()
       );

        return $this;
    }

    final protected function configureCommandDescText(): CommandConfigurationInterface
    {
        return $this->invokeIfStrArgNotNull(
            $this->getCommandDescText(), fn ($desc) => $this->getCommand()->setDescription($desc)
        );
    }

    final protected function configureCommandHelpText(): CommandConfigurationInterface
    {
        return $this->invokeIfStrArgNotNull(
            $this->getCommandHelpText(), fn ($help) => $this->getCommand()->setHelp($help)
        );
    }

    final protected function configureCommandAliasSet(): CommandConfigurationInterface
    {
        return $this->invokeIfStrArgNotNull(
            $this->getCommandAliasSet(), fn ($keys) => $this->getCommand()->setAliases($keys)
        );
    }

    /**
     * @param string|string[]|null $v
     *
     * @return $this
     */
    private function invokeIfStrArgNotNull(string | array | null $v, Closure $c): self
    {
        if (null !== $v && false === empty($v)) {
            $c($v);
        }

        return $this;
    }
}
