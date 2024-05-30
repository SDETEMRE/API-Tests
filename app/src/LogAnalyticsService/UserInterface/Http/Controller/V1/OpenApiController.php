<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\UserInterface\Http\Controller\V1;

use App\LogAnalyticsService\UserInterface\Http\Response\Error\Factory\ErrorFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/openapi.{_format}', name: 'app_openapi_analytics', defaults: ['_format' => 'html'], methods: ['GET'])]
final class OpenApiController extends AbstractController
{
    public function __construct(
        private readonly string $openApiFilePath
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $content = file_get_contents($this->openApiFilePath);

        return match ($request->getRequestFormat()) {
            'html' => $this->render('openapi.html.twig', [
                'route' => $this->generateUrl('app_openapi_analytics', ['_format' => 'yaml']),
                'title' => 'Analytics Service',
            ]),
            'yaml' => new Response(
                false !== $content ? $content : '',
                Response::HTTP_OK,
                ['Content-Type' => 'application/x-yaml']
            ),
            default => $this->json(
                ErrorFactory::ofOpenApi(
                    'Invalid format provided.',
                    ErrorFactory::ERROR_BAD_REQUEST
                ),
                Response::HTTP_BAD_REQUEST
            )
        };
    }
}
