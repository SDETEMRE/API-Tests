<?php

declare(strict_types=1);

namespace App\Tests\Integration\LogAnalyticsService\UserInterface\Http\Controller\V1;

use App\Tests\Mock\Infrastructure\Repository\ServiceLogRepositorySpy;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use const JSON_THROW_ON_ERROR;

final class TruncateServiceLogControllerTest extends WebTestCase
{
    public function test_truncate_service_logs_works_as_expected(): void
    {
        $browser = self::createClient();

        ServiceLogRepositorySpy::$shouldReturnUnHandleableException = false;

        $browser->request(
            method: 'DELETE',
            uri: '/truncate',
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function test_truncate_service_logs_return_service_not_available_exception(): void
    {
        $browser = self::createClient();

        ServiceLogRepositorySpy::$shouldReturnUnHandleableException = true;

        $browser->request(
            method: 'DELETE',
            uri: '/truncate',
        );

        self::assertResponseStatusCodeSame(Response::HTTP_SERVICE_UNAVAILABLE);

        $actual = json_decode($browser->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('/log-analytics-service/errors/truncate_service_log/undefined', $actual['type']);
    }
}
