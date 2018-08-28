<?php

/**
 * This file supports command, which is responsible for categories import.
 *
 * PHP version 7.1.16
 *
 * @category  Command
 * @package   Virtua_Internship
 * @author    Maciej Skalny <contact@wearevirtua.com>
 * @copyright 2018 Copyright (c) Virtua (http://wwww.wearevirtua.com)
 * @license   GPL http://opensource.org/licenses/gpl-license.php
 * @link      https://github.com/maciejskalny/backup-symfony
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\CsvActions;

/**
 * Class CategoryExportCommand
 *
 * @category Class
 * @package  App\Command
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class CategoryImportCommand extends Command
{
    /**
     * Path of file, from you want to import categories.
     *
     * @var string
     */
    private $file;

    /**
     * CsvActions service.
     *
     * @var CsvActions
     */
    private $csvActionsService;

    /**
     * CategoryImportCommand constructor.
     *
     * @param CsvActions  $csvActionsService
     * @param string|null $file
     */
    public function __construct(CsvActions $csvActionsService, string $file = null)
    {
        $this->csvActionsService = $csvActionsService;
        parent::__construct();
    }

    /**
     * Configuring command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('app:category-import')
            ->setDescription('Imports categories.')
            ->setHelp('This command allows you to import categories.')
            ->addArgument('file', $this->file ? InputArgument::REQUIRED : InputArgument::REQUIRED, 'Path to file.');
    }

    /**
     * Supports command actions.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['Category import', '==============']);

        $this->csvActionsService->import('category', $input->getArgument('file'));
        $output->writeln('Success!');
    }
}
