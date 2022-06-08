<?php
namespace App\Command;

use App\Services\OrderConvert\CsvConvert;
use App\Services\OrderImport\JsonlImport;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:process-order',
    description: 'Command to process Order Files.'
)]

class ProcessOrderFile extends Command
{
    private $orderFileUrl = 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1/orders.jsonl';

    protected JsonlImport $jsonLinesImportHandler;
    protected CsvConvert $csvConvertHandler;


    public function __construct(
        JsonlImport $jsonLinesImportHandler,
        CsvConvert $csvConvertHandler
    )
    {
        $this->jsonLinesImportHandler = $jsonLinesImportHandler;
        $this->csvConvertHandler = $csvConvertHandler;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to process order file')
            ->addArgument('email', InputArgument::OPTIONAL, 'User password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try{
            // orders from order file
            $orders = $this->jsonLinesImportHandler->setOrderFileUrl($this->orderFileUrl)->getOrdersFromFile();

            // preparing csv file if orders are present
            if (!empty($orders)) {
                $this->csvConvertHandler->convertOrdersToFile($orders);
            }
            $output->writeln('Process completed without any errors');
        } catch (\Exception $exception) {
            $output->write([
                'Some error occured while processing the orders: ',
                $exception->getMessage()
            ]);
        }
        return Command::SUCCESS;
    }
}

