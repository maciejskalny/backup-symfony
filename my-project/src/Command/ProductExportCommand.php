<?php

/**
 * This file supports command, which is responsible for products export.
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
class ProductExportCommand extends Command
{
    /**
     * Name of file, where you want to save exported data.
     * @var string
     */
    private $file;

    /**
     * Products you want to export. Optional.
     * @var string
     */
    private $products;

    /**
     * CsvActions service.
     * @var CsvActions
     */
    private $csvActionsService;

    /**
     * ProductExportCommand constructor.
     * @param CsvActions $csvActionsService
     * @param string|null $file
     * @param string|null $products
     */
    public function __construct(CsvActions $csvActionsService, string $file=null, string $products=null)
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
            ->setName('app:product-export')
            ->setDescription('Exports products.')
            ->setHelp('This command allows you to export products.')
            ->addArgument('file', $this->file ? InputArgument::REQUIRED : InputArgument::REQUIRED, 'File name.')
            ->addArgument('product', $this->products ? InputArgument::IS_ARRAY : InputArgument::OPTIONAL, 'Id of products you want to export separated by ,');
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
            'Product export',
            '=============='
        ]);

        $this->csvActionsService->export('product', $input->getArgument('file'), $input->getArgument('product'));
        $output->writeln('Success!');
    }
}