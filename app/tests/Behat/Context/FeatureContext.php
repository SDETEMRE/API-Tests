<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\LogAnalyticsService\Application\StoreServiceLog\Dto\StoreLogServiceRequest;
use App\LogAnalyticsService\Application\StoreServiceLog\StoreLogService;
use App\LogAnalyticsService\Domain\Model\HttpMethod;
use App\LogAnalyticsService\Domain\Model\LogDate;
use App\LogAnalyticsService\Domain\Model\Protocol;
use App\LogAnalyticsService\Domain\Model\Route;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use App\LogAnalyticsService\Domain\Model\StatusCode;
use App\LogAnalyticsService\Infrastructure\Repository\DoctrineDbalServiceLogRepository;
use Behat\Gherkin\Node\TableNode;
use DateTimeImmutable;
use Imbo\BehatApiExtension\Context\ApiContext;

final class FeatureContext extends ApiContext
{
    public function __construct(
        private readonly DoctrineDbalServiceLogRepository $doctrineDbalServiceLogRepository,
    ) {
    }

    /** @Given I want to store logs with the following data: */
    public function storeLogsIntoDatabase(TableNode $table): void
    {
        /** @var array<string,array{name:string,value:string}> */
        $rows = $table->getColumnsHash();
        foreach ($rows as $row) {
            $this->doctrineDbalServiceLogRepository->store(
                serviceName: new ServiceName($row['service_name']),
                startDate: new LogDate(new DateTimeImmutable($row['date_time'])),
                httpMethod: HttpMethod::from($row['http_method']),
                route: new Route($row['route']),
                protocol: new Protocol($row['protocol']),
                statusCode: new StatusCode((int)$row['http_code']),
            );
        }
    }
}
