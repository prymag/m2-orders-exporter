<?php

namespace Prymag\OrdersExporter\Console;

use Prymag\OrdersExporter\Logger\Logger;
use Prymag\OrdersExporter\Service\CsvSenderService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Prymag\OrdersExporter\Service\ExporterService;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;

class Exporter extends Command {
    //
    protected $_csvSenderService;
    protected $_exporterService;
    protected $_logger;
    protected $_state;

    const KEY_STOREIDS = 'store_ids';
    const KEY_FILENAMES = 'filenames';
    const KEY_RANGESTART = 'range_start';
    const KEY_DELIMITER = 'delimiter';
    const KEY_STOPSEND = 'stop_send';

    public function __construct(
        CsvSenderService $csvSenderService,
        ExporterService $exporterService,
        Logger $logger,
        State $state
    ) {
        # code...
        $this->_csvSenderService = $csvSenderService;
        $this->_exporterService = $exporterService;
        $this->_logger = $logger;
        $this->_state = $state;

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
    }

    protected function getOptionsList()
    {
        return [
            new InputOption(self::KEY_STOREIDS, null, InputOption::VALUE_REQUIRED, 'Comma separated store ids'),
            new InputOption(self::KEY_FILENAMES, null, InputOption::VALUE_OPTIONAL, 'Comma separate filenames that matches comma separated store ids'),
            new InputOption(self::KEY_RANGESTART, null, InputOption::VALUE_OPTIONAL, 'A date/time string. Valid formats are explained in Date and Time Formats'),
            new InputOption(self::KEY_DELIMITER, null, InputOption::VALUE_OPTIONAL, 'Delimiter for the CSV file, defaults to comma'),
            new InputOption(self::KEY_STOPSEND, null, InputOption::VALUE_OPTIONAL, 'Prevent sending of attachment'),
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
            // Set the code to backend
            // ensure config will always pull the defaults when possible.
            $this->_state->setAreaCode(Area::AREA_ADMINHTML);

            $this->_logger->info('Running exporter...');

            $params = [
                'storeIds' => $input->getOption(self::KEY_STOREIDS),
                'filenames' => $input->getOption(self::KEY_FILENAMES),
                'rangeStart' => $input->getOption(self::KEY_RANGESTART),
                'delimiter' => $input->getOption(self::KEY_DELIMITER),
            ];

            if (!$params['rangeStart']) {
                $params['rangeStart'] = '-24 hours';
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

            if ($input->getOption(self::KEY_STOPSEND) == '') {
                $output->writeln('Sending attachments...');
                $this->_csvSenderService->send($results);
                $output->writeln('Sending successful');
            }

        } catch (\Exception $e) {
            $output->writeln("<error>An error encountered.: {$e->getMessage()}</error>");

            $this->_logger->critical("Error", ['exception' => $e]);
        }
    }
}