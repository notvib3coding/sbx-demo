<?php

namespace App\Controller;

use App\Note\NewNote;
use App\Note\NoteStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notes')]
final class NoteController extends AbstractController
{
    public function __construct(
        private readonly NoteStore $notes,
        #[Autowire(env: 'API_TOKEN')] private readonly string $apiToken,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->notes->all());
    }

    /** Writing needs the API token: Authorization: Bearer <API_TOKEN>. */
    #[Route('', methods: ['POST'])]
    public function create(Request $request, #[MapRequestPayload] NewNote $payload): JsonResponse
    {
        if (!$this->authorized($request)) {
            return $this->json(['error' => 'missing or wrong API token'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($this->notes->add($payload->text), Response::HTTP_CREATED);
    }

    private function authorized(Request $request): bool
    {
        $given = (string) preg_replace('/^Bearer\s+/i', '', (string) $request->headers->get('Authorization'));

        return '' !== $this->apiToken && hash_equals($this->apiToken, $given);
    }
}
