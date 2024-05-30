<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Infrastructure;

use App\LogAnalyticsService\Domain\Model\FilePath;
use App\LogAnalyticsService\Domain\Model\HttpMethod;
use App\LogAnalyticsService\Domain\Model\LogDate;
use App\LogAnalyticsService\Domain\Model\Protocol;
use App\LogAnalyticsService\Domain\Model\Route;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use App\LogAnalyticsService\Domain\Model\StatusCode;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogImporterInterface;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use App\LogAnalyticsService\Infrastructure\Extractor\LogParser;
use DateTimeImmutable;
use SplFileObject;

final readonly class ServiceLogImporter implements ServiceLogImporterInterface
{
    public function __construct(
        private ServiceLogRepositoryInterface $serviceLogsRepository,
        private LogParser $logParser,
    ) {
    }

    public function import(FilePath $filePath): void
    {

        $fileIterator = new SplFileObject((string)$filePath, 'r');
        $fileIterator->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);

        while (!$fileIterator->eof()) {
            $line = $fileIterator->current();

            $parsedLine = $this->logParser->parse($line);
            $this->serviceLogsRepository->store(
                serviceName: new ServiceName($parsedLine->serviceName),
                startDate: new LogDate(new DateTimeImmutable($parsedLine->logDate)),
                httpMethod: HttpMethod::from($parsedLine->httpMethod),
                route: new Route($parsedLine->route),
                protocol: new Protocol($parsedLine->protocol),
                statusCode: new StatusCode($parsedLine->statusCode),
            );

            $fileIterator->next();
        }
    }
}
