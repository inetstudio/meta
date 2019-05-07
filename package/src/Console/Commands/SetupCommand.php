<?php

namespace InetStudio\MetaPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:meta-package:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup meta package';

    /**
     * Инициализация команд.
     */
    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Meta setup',
                'command' => 'inetstudio:meta-package:meta:setup',
            ],
        ];
    }
}
