<?php

namespace App\Http\Controllers\Api;

use App\Domain\Microsite\Actions\StoreMicrositeAction;
use App\Domain\Microsite\Actions\UpdateMicrositeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Microsite\StoreMicrositeRequest;
use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Repositories\EloquentMicrositeRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class micrositeController extends Controller
{
    use AuthorizesRequests;
    private $micrositesRepository;

    public function __construct(EloquentMicrositeRepository $repository)
    {
        $this->micrositesRepository = $repository;
    }

    public function index(): Response
    {
        $this->authorize('viewAny', Microsite::class);
        $microsites = $this->micrositesRepository->getAllWithCategories();
        return Inertia::render('Microsites/Index', compact('microsites'));
    }

    public function create(): Response
    {
        $this->authorize('create', Microsite::class);
        $arrayConstants = $this->micrositesRepository->getCommonData();
        return Inertia::render('Microsites/Create', $arrayConstants);
    }

    public function store(StoreMicrositeRequest $request, StoreMicrositeAction $storeAction): RedirectResponse
    {
        $this->authorize('store', Microsite::class);
        return $storeAction->execute($request->validated());
    }

    public function show(string $id): Response
    {
        $this->authorize('view', Microsite::class);
        $microsite = $this->micrositesRepository->getWithCategories($id);

        return Inertia::render('Microsites/Show', compact('microsite'));
    }

    public function edit(string $id): Response
    {
        $this->authorize('edit', Microsite::class);
        $microsite = $this->micrositesRepository->find($id);
        $arrayConstants = $this->micrositesRepository->getCommonData();

        return Inertia::render('Microsites/Edit', compact('microsite', 'arrayConstants'));
    }

    public function update(UpdateMicrositeRequest $request, string $id, UpdateMicrositeAction $updateAction): RedirectResponse
    {
        $this->authorize('update', Microsite::class);
        return $updateAction->execute($id, $request->validated());
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', Microsite::class);
        $this->micrositesRepository->delete($id);
        return to_route('microsites.index');
    }

    public function welcomeIndex(): Response
    {
        $microsites = $this->micrositesRepository->getAllWithCategories();
        return Inertia::render('Welcome', compact('microsites'));

    }
    public function paymentForm(): Response
    {
        return Inertia::render('PaymentForm');
    }
}
