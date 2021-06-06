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
use App\Component\Console\Style\AppStyleWrapper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

interface CommandConfigurationInterface
{
    public function __construct(TranslatorInterface $translator);

    public function getTranslator(): TranslatorInterface | LocaleAwareInterface;

    public function setEnabled(bool $enabled = true): self;

    public function isEnabled(): bool;

    public function setCommand(AbstractCommand $command, bool $enabled = true): self;

    public function getCommand(): AbstractCommand;

    public function getCommandDefinition(): InputDefinition;

    public function getCommandReference(): string;

    public function configure(): self;

    public function setUpExec(InputInterface $i, OutputInterface $o): AppStyleWrapper;

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
    public function getCommandDescText(): string | null;

    /**
     * @return string|null
     */
    public function getCommandHelpText(): string | null;

    /**
     * @return string[]|null
     */
    public function getCommandAliasSet(): array | null;
}
