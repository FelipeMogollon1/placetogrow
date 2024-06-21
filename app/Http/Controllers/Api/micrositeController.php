<?php

namespace App\Http\Controllers\Api;

use App\Domain\Microsite\Actions\StoreMicrositeAction;
use App\Domain\Microsite\Actions\UpdateMicrositeAction;
use App\Domain\Microsite\Actions\UpdatePartialMicrositeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Microsite\StoreMicrositeRequest;
use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Http\Requests\Microsite\UpdatePartialMicrositeRequest;
use App\Infrastructure\Persistence\Repositories\EloquentMicrositeRepository;
use Illuminate\Http\JsonResponse;

class micrositeController extends Controller
{
    private $micrositesRepository;

    public function __construct(EloquentMicrositeRepository $repository)
    {
        $this->micrositesRepository = $repository;
    }

    public function  index(): iterable
    {
        return $this->micrositesRepository->all();
    }

    public function store(StoreMicrositeRequest $request, StoreMicrositeAction $storeMicrositeAction): JsonResponse
    {
        $microsite = $storeMicrositeAction->execute($request->validated());
        return response()->json($microsite, 201);
    }

     public function show(string $id): JsonResponse
    {
        $microsite = $this->micrositesRepository->find($id);

        if (!$microsite) {
            return response()->json(['error' => 'Microsite not found'], 404);
        }

        return response()->json($microsite, 200);
    }

   public function update(UpdateMicrositeRequest $request, string $id, UpdateMicrositeAction $updateAction): JsonResponse
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['error' => 'Bad Request'], 400);
        }

        $microsite = $updateAction->execute($id, $request->validated());
        return response()->json($microsite, 200);
    }

    public function updatePartial(UpdatePartialMicrositeRequest $request, string $id, UpdatePartialMicrositeAction $updateAction): JsonResponse
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['error' => 'Bad Request'], 400);
        }

        $validatedData = $request->validated();
        $microsite = $updateAction->execute($id, $validatedData);
        return response()->json($microsite, 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->micrositesRepository->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Microsite not found or could not be deleted'], 404);
        }

        return response()->json(['message' => 'Microsite eliminado exitosamente'], 200);
    }
}
