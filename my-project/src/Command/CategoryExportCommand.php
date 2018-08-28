<?php
/**
 * This file supports command, which is responsible for categories export.
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
class CategoryExportCommand extends Command
{

    /**
     * Name of file, where you want to save exported data.
     *
     * @var string
     */
    private $file;

    /**
     * Categories you want to export. Optional.
     *
     * @var string
     */
    private $categories;

    /**
     * CsvActions service.
     *
     * @var CsvActions
     */
    private $csvActionsService;

    /**
     * CategoryExportCommand constructor.
     *
     * @param CsvActions  $csvActionsService
     * @param string|null $file
     * @param string|null $categories
     */
    public function __construct(CsvActions $csvActionsService, string $file = null, string $categories = null)
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
            ->setName('app:category-export')
            ->setDescription('Exports categories.')
            ->setHelp('This command allows you to export categories.')
            ->addArgument('file', $this->file ? InputArgument::REQUIRED : InputArgument::REQUIRED, 'File name.')
            ->addArgument('category', $this->categories ? InputArgument::IS_ARRAY : InputArgument::OPTIONAL, 'Id of categories you want to export separated by ,');
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
        $output->writeln(['Category export', '==============']);

        $this->csvActionsService->export('category', $input->getArgument('file'), $input->getArgument('category'));
        $output->writeln('Success!');
    }
}
