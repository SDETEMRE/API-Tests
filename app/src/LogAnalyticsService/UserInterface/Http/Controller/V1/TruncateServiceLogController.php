<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\UserInterface\Http\Controller\V1;

use App\LogAnalyticsService\Application\TruncateServiceLog\TruncateServiceLogService;
use App\LogAnalyticsService\UserInterface\Http\Response\Error\Factory\ErrorFactory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/truncate', methods: ['DELETE'])]
final class TruncateServiceLogController extends AbstractController
{
    public function __construct(
        private readonly TruncateServiceLogService $truncateServiceLogService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            ($this->truncateServiceLogService)();
        } catch (Throwable $e) {
            $this->logger->error('An error happened, please check the logs', [
                'exception' => $e,
            ]);

            return $this->json(
                ErrorFactory::ofTruncateServiceLogs(
                    'Something went wrong.',
                    ErrorFactory::ERROR_UNDEFINED
                ),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
