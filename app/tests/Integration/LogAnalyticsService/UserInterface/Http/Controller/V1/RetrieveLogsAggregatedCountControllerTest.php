<?php

declare(strict_types=1);

namespace App\Tests\Integration\LogAnalyticsService\UserInterface\Http\Controller\V1;

use App\Tests\Mock\Infrastructure\Repository\ServiceLogRepositorySpy;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use const JSON_THROW_ON_ERROR;

final class RetrieveLogsAggregatedCountControllerTest extends WebTestCase
{
    public function test_retrieve_aggregated_count_works_as_expected(): void
    {
        $browser = self::createClient();

        ServiceLogRepositorySpy::$aggregatedCount = 10;

        $browser->request(
            method: 'GET',
            uri: sprintf(
                '/count?serviceNames[]=%s&serviceNames[]=%s&startDate=%s&endDate=%s&statusCode=%s',
                'USER-SERVICE',
                'INVOICE-SERVICE',
                '2024-04-01T00:00:00Z',
                '2024-04-30T23:59:59Z',
                200
            ),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $actual = json_decode($browser->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(10, $actual['counter']);
    }

    public function test_retrieve_aggregated_count_should_return_validation_exception(): void
    {
        $browser = self::createClient();

        ServiceLogRepositorySpy::$aggregatedCount = 10;

        $browser->request(
            method: 'GET',
            uri: sprintf(
                '/count?serviceNames[]=%s&serviceNames[]=%s&startDate=%s&endDate=%s&statusCode=%s',
                'USER-SERVICE',
                'INVOICE-SERVICE',
                '2024-04-01T00:00:00Z',
                '2024-04-30T23:59:59Z',
                700
            ),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $actual = json_decode($browser->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('/log-analytics-service/errors/retrieve_aggregated_log/bad-request', $actual['type']);
    }

    public function test_retrieve_aggregated_count_should_return_service_not_available_exception(): void
    {
        $browser = self::createClient();

        ServiceLogRepositorySpy::$shouldReturnUnHandleableException = true;

        $browser->request(
            method: 'GET',
            uri: sprintf(
                '/count?serviceNames[]=%s&serviceNames[]=%s&startDate=%s&endDate=%s&statusCode=%s',
                'USER-SERVICE',
                'INVOICE-SERVICE',
                '2024-04-01T00:00:00Z',
                '2024-04-30T23:59:59Z',
                200
            ),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_SERVICE_UNAVAILABLE);

        $actual = json_decode($browser->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('/log-analytics-service/errors/retrieve_aggregated_log/undefined', $actual['type']);
    }
}
