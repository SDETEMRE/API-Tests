<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\UserInterface\CLI\Command;

use App\LogAnalyticsService\Application\StoreServiceLog\Dto\StoreLogServiceRequest;
use App\LogAnalyticsService\Application\StoreServiceLog\Exception\LogsImportFailedException;
use App\LogAnalyticsService\Application\StoreServiceLog\StoreLogService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class StoreLogsInDatabaseCommand extends Command
{
    protected static $defaultName = 'app:service-logs:store';

    public function __construct(
        private readonly string $logFilePath,
        private readonly StoreLogService $storeLogService,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Stores the service logs from log file to the DB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->printStdout($output, 'Storing service logs to DB started', $this->logger);

        try {
            $filePath = $this->logFilePath;

           ($this->storeLogService)(new StoreLogServiceRequest($filePath));
        } catch (LogsImportFailedException) {
            return self::FAILURE;
        } catch (Throwable $exception) {
            $this->logger->error('Something went wrong', [
                'exception_message' => $exception->getMessage(),
                'exception'         => $exception,
            ]);
            $this->printStdout($output, 'Storing service logs to DB Failed', $this->logger);
            return self::FAILURE;
        }

        $this->printStdout($output, 'Storing service logs to DB Succeeded', $this->logger);
        return self::SUCCESS;
    }

    private function printStdout(OutputInterface $output, string $message, LoggerInterface $logger): void
    {
        $output->writeln(sprintf('<info>%s</info>', $message));

        $logger->warning(sprintf('[%s] %s', $this->getName(), $message));
    }
}
