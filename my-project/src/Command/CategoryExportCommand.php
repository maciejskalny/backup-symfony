<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\CsvActions;

class CategoryExportCommand extends Command
{
    private $file;
    private $categories;
    private $csvActionsService;

    public function __construct(CsvActions $csvActionsService, $file=null, $categories=null)
    {
        $this->csvActionsService = $csvActionsService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:category-export')
            ->setDescription('Exports categories.')
            ->setHelp('This command allows you to export categories.')
            ->addArgument('file', $this->file ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'File name.')
            ->addArgument('category', $this->categories ? InputArgument::OPTIONAL : InputArgument::IS_ARRAY, 'Id of categories you want to export');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Category export',
            '=============='
        ]);

        $this->csvActionsService->exportCommand('category', $input->getArgument('file'), $input->getArgument('category'));

        $output->writeln('Success!');
    }

}