<?php

/**
 * This file supports command, which is responsible for products import.
 * @category Command
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\CsvActions;

/**
 * Class ProductExportCommand
 * @package App\Command
 */
class ProductImportCommand extends Command
{
    /**
     * Path of file, from you want to import products.
     * @var string
     */
    private $file;

    /**
     * CsvActions service.
     * @var CsvActions
     */
    private $csvActionsService;

    /**
     * ProductImportCommand constructor.
     * @param CsvActions $csvActionsService
     * @param string|null $file
     */
    public function __construct(CsvActions $csvActionsService, string $file=null)
    {
        $this->csvActionsService = $csvActionsService;
        parent::__construct();
    }

    /**
     * Configuring command.
     */
    protected function configure()
    {
        $this
            ->setName('app:product-import')
            ->setDescription('Imports products.')
            ->setHelp('This command allows you to import products.')
            ->addArgument('file', $this->file ? InputArgument::REQUIRED : InputArgument::REQUIRED, 'Path to file.');
    }

    /**
     * Supports command actions.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Product import',
            '=============='
        ]);

        $this->csvActionsService->import('product', $input->getArgument('file'));
        $output->writeln('Success!');
    }
}