<?php

namespace Prymag\OrdersExporter\Console;

use Prymag\OrdersExporter\Logger\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Prymag\OrdersExporter\Service\ExporterService;

class Exporter extends Command {
    //
    protected $_exporterService;
    protected $_logger;

    const KEY_STOREIDS = 'store_ids';
    const KEY_FILENAMES = 'filenames';
    const KEY_RANGE = 'range';

    public function __construct(
        ExporterService $exporterService,
        Logger $logger
    ) {
        # code...
        $this->_exporterService = $exporterService;
        $this->_logger = $logger;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('prymag:orders:exporter')
            ->setDescription('ETM Order Exporter')
            ->setDefinition($this->getOptionsList());

        parent::configure();
    }

    protected function getOptionsList()
    {
        return [
            new InputOption(self::KEY_STOREIDS, null, InputOption::VALUE_REQUIRED, 'Comma separated store ids'),
            new InputOption(self::KEY_FILENAMES, null, InputOption::VALUE_OPTIONAL, 'Comma separate filenames'),
            new InputOption(self::KEY_RANGE, null, InputOption::VALUE_OPTIONAL, 'A date/time string. Valid formats are explained in Date and Time Formats'),
        ];
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
            $this->_logger->info('Running exporter...');

            $params = [
                'storeIds' => $input->getOption(self::KEY_STOREIDS),
                'filenames' => $input->getOption(self::KEY_FILENAMES),
                'range' => $input->getOption(self::KEY_RANGE)
            ];

            if (!$params['range']) {
                $params['range'] = '-24 hours';
            }

            $this->_logger->info("Params:", $params);

            if (!$params['storeIds'])  {
                throw new \Exception('Invalid store IDS');
            }

            $results = $this->_exporterService->process($params);

            foreach($results as $result) {
                $this->_logger->info('Success', $result);
                $output->writeln("<info>Processed: {$result['total']} orders for Store ID: {$result['storeId']} </info>");
            }

        } catch (\Exception $e) {
            $output->writeln("<error>An error encountered.: {$e->getMessage()}</error>");

            $this->_logger->critical("Error", ['exception' => $e]);
        }
    }
}