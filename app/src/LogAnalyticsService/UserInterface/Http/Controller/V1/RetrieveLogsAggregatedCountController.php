<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\UserInterface\Http\Controller\V1;

use App\LogAnalyticsService\Application\RetrieveAggregatedCount\Dto\RetrieveServiceLogAggregatedCountRequest;
use App\LogAnalyticsService\Application\RetrieveAggregatedCount\RetrieveLogsAggregatedCountService;
use App\LogAnalyticsService\Domain\Exception\ValidationException;
use App\LogAnalyticsService\UserInterface\Http\Response\Error\Factory\ErrorFactory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

use function is_array;

#[Route(path: '/count', methods: ['GET'])]
final class RetrieveLogsAggregatedCountController extends AbstractController
{
    public function __construct(
        private readonly RetrieveLogsAggregatedCountService $retrieveAggregatedCountService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $retrieveAggregatedCountRequest = $this->createRequest($request);
        } catch (JsonException) {
            return $this->json(
                ErrorFactory::ofRetrieveAggregatedCount(
                    'Invalid request content.',
                    ErrorFactory::ERROR_BAD_REQUEST
                ),
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $aggregatedCount = ($this->retrieveAggregatedCountService)($retrieveAggregatedCountRequest);
        } catch (ValidationException $e) {
            return $this->json(
                ErrorFactory::ofRetrieveAggregatedCount(
                    $e->getMessage(),
                    ErrorFactory::ERROR_BAD_REQUEST,
                    $e->violations()
                ),
                Response::HTTP_BAD_REQUEST
            );
        } catch (Throwable $e) {
            $this->logger->error('An error happened, please check the logs', [
                'exception' => $e,
            ]);

            return $this->json(
                ErrorFactory::ofRetrieveAggregatedCount(
                    'Something went wrong.',
                    ErrorFactory::ERROR_UNDEFINED
                ),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->json(
            [
                'counter' => $aggregatedCount->count,
            ],
            Response::HTTP_OK
        );
    }

    private function createRequest(Request $request): RetrieveServiceLogAggregatedCountRequest
    {
        $serviceNames = $request->get('serviceNames') ?? [];
        if (!is_array($serviceNames)) {
            throw new JsonException('Provided serviceNames is not an array');
        }

        return new RetrieveServiceLogAggregatedCountRequest(
            $serviceNames,
            $request->get('startDate'),
            $request->get('endDate'),
            null !== $request->get('statusCode') ? (int) $request->get('statusCode') : null
        );
    }
}
