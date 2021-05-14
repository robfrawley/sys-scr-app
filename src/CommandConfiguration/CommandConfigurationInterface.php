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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

interface CommandConfigurationInterface
{
    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator);

    /**
     * @return TranslatorInterface|LocaleAwareInterface
     */
    public function getTranslator(): TranslatorInterface|LocaleAwareInterface;

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled(bool $enabled = true): self;

    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param AbstractCommand $command
     * @param bool            $enabled
     *
     * @return $this
     */
    public function setCommand(AbstractCommand $command, bool $enabled = true): self;

    /**
     * @return AbstractCommand
     */
    public function getCommand(): AbstractCommand;

    /**
     * @return InputDefinition
     */
    public function getCommandDefinition(): InputDefinition;

    /**
     * @return string
     */
    public function getCommandReference(): string;

    /**
     * @return $this
     */
    public function configure(): self;

    /**
     * @param InputInterface  $i
     * @param OutputInterface $o
     *
     * @return AppStyle
     */
    public function setUpExec(InputInterface $i, OutputInterface $o): AppStyle;

    /**
     * @return InputArgument[]
     */
    public function getCommandDefGlobalArgs(): array;

    /**
     * @return InputOption[]
     */
    public function getCommandDefGlobalOpts(): array;

    /**
     * @return InputArgument[]
     */
    public function getCommandDefCustomArgs(): array;

    /**
     * @return InputOption[]
     */
    public function getCommandDefCustomOpts(): array;

    /**
     * @return string|null
     */
    public function getCommandDescText(): string|null;

    /**
     * @return string|null
     */
    public function getCommandHelpText(): string|null;

    /**
     * @return string[]|null
     */
    public function getCommandAliasSet(): array|null;
}
