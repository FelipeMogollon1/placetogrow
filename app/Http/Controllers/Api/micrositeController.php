<?php

namespace App\Http\Controllers\Api;

use App\Domain\Microsite\Actions\StoreMicrositeAction;
use App\Domain\Microsite\Actions\UpdateMicrositeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Microsite\StoreMicrositeRequest;
use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Infrastructure\Persistence\Repositories\EloquentMicrositeRepository;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class micrositeController extends Controller
{
    private $micrositesRepository;

    public function __construct(EloquentMicrositeRepository $repository)
    {
        $this->micrositesRepository = $repository;
    }

    public function  index(): Response
    {
        $microsites = $this->micrositesRepository->getAllWithCategories();
        return Inertia::render('Microsites/Index', compact('microsites'));
    }

    public function create(): Response
    {
        $arrayConstants = $this->micrositesRepository->getCommonData();

        return Inertia::render('Microsites/Create', $arrayConstants);
    }

    public function store(StoreMicrositeRequest $request, StoreMicrositeAction $storeAction): RedirectResponse
    {
        return $storeAction->execute($request->validated());
    }

     public function show(string $id): Response
     {
        $microsite = $this->micrositesRepository->getWithCategories($id);

        return Inertia::render('Microsites/Show', compact('microsite'));
    }

    public function edit(string $id): Response
    {
        $microsite = $this->micrositesRepository->find($id);
        $arrayConstants = $this->micrositesRepository->getCommonData();

        return Inertia::render('Microsites/Edit', compact('microsite','arrayConstants'));
    }

    public function update(UpdateMicrositeRequest $request, string $id, UpdateMicrositeAction $updateAction): RedirectResponse
    {
        return $updateAction->execute($id, $request->validated());
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->micrositesRepository->delete($id);
        return to_route('microsites.index');
    }

    public function welcomeIndex(): Response
    {
        $microsites = $this->micrositesRepository->getAllWithCategories();
        return Inertia::render('Welcome', compact('microsites'));

    }

}
