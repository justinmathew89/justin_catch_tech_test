<?php
namespace App\Command;

use App\Services\OrderConvert\CsvConvert;
use App\Services\OrderImport\Exceptions\OrderFileMissingException;
use App\Services\OrderImport\Exceptions\ImportNotSupportedException;
use App\Services\OrderImport\IFileImport;
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
    protected $fileImportHandler;
    protected CsvConvert $csvConvertHandler;


    public function __construct(
        CsvConvert $csvConvertHandler
    )
    {
        $this->csvConvertHandler = $csvConvertHandler;
        parent::__construct();
    }

    /**
     * configuring the command
     * @return void
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to process order file')
            ->addArgument('email', InputArgument::OPTIONAL, 'User password');
    }

    /**
     * Main command execute function
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $orderFileUrl = $_ENV['ORDER_FILE_PATH'];
            $this->setFileImportHandler($orderFileUrl);

            $orders = $this->fileImportHandler->getOrdersFromFile();

            // preparing csv file if orders are present
            if (!empty($orders)) {
                $this->csvConvertHandler->convertOrdersToFile($orders);
            }
            $output->writeln('Process completed without any errors. Order export file saved in public/out.csv');
        } catch(ImportNotSupportedException $e) {
            $output->write([
                'Import for this filetype is not implemented yet'
            ]);
        } catch(OrderFileMissingException $e) {
            $output->write([
                'Some error occured while processing the order File: ',
                $e->getMessage()
            ]);
        } catch(\Exception $exception) {
            $output->write([
                'Some error occured while processing the orders: ',
                $exception->getMessage()
            ]);
        }
        return Command::SUCCESS;
    }

    /**
     * This method loads the appropriate file converter for order file provided in the env file
     * @param $orderFileUrl
     * @return void
     * @throws ImportNotSupportedException
     */
    private function setFileImportHandler($orderFileUrl)
    {
        $fileExtension = pathinfo($orderFileUrl, PATHINFO_EXTENSION);
        $className = 'App\Services\OrderImport\\' . ucfirst($fileExtension) . 'Import';
        if (!class_exists($className)) {
            throw new ImportNotSupportedException();
        }
        $this->fileImportHandler = new $className();
        if (!$this->fileImportHandler || !$this->fileImportHandler instanceof IFileImport) {
            throw new ImportNotSupportedException();
        }
        $this->fileImportHandler->setOrderFileUrl($orderFileUrl);
    }
}

