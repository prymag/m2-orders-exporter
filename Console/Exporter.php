<?php

namespace Prymag\OrdersExporter\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Prymag\OrdersExporter\Service\ExporterService;

class Exporter extends Command {
    //
    protected $_exporterService;

    public function __construct(
        ExporterService $exporterService
    ) {
        # code...
        $this->_exporterService = $exporterService;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('prymag:orders:exporter');
        $this->setDescription('Running exporter');
        parent::configure();
    }

    /**
     * Execute the command
    *
    * @param InputInterface $input
    * @param OutputInterface $output
    *
    * @return null|int
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $result = $this->_exporterService->process();
            $output->writeln("<info>Processed: {$result['total']} </info>");
        } catch (Exception $e) {
            $output->writeln('<error>An error encountered.</error>');
        }
    }
}