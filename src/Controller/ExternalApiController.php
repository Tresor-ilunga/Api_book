<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ExternalApiController
 * @author Tresor-ilunga <19im065@esisalama.org>
 */
class ExternalApiController extends AbstractController
{
    /**
     * Cette méthode fait appel à la route https://api.github.com/repos/symfony/symfony-docs
     * récupère les données et les transmet telles quelles
     *
     * @param HttpClientInterface $httpClient
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/api/external/getSfDoc', name: 'external.api', methods: ['GET'])]
    public function getSymfonyDoc(HttpClientInterface $httpClient): JsonResponse
    {
        $response = $httpClient->request(
            'GET',
            'https://api.github.com/repos/symfony/symfony-docs'
        );
        return new JsonResponse($response->getContent(), $response->getStatusCode(), [], true);
    }
}
