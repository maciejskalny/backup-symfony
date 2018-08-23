<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CategoryExportCommand extends Command
{
    private $fileName;
    private $id;

    public function __construct($name=null, $id=null)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:category-export')
            ->setDescription('Exports categories.')
            ->setHelp('This command allows you to export categories.')
            ->addArgument('name', $this->fileName ? InputArgument::REQUIRED, 'File name.')
            ->addArgument('id', $this->id ? InputArgument::OPTIONAL : InputArgument::IS_ARRAY, 'Id of categories you want to export');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Category export',
            '==============',
            '',
        ]);

        $output->writeln($this->getDescription());

        $output->writeln('Abc');
        parent::execute($input, $output); // TODO: Change the autogenerated stub
    }

}