<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Abilities;
use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Domain\Microsite\Actions\DestroyMicrositeAction;
use App\Domain\Microsite\Actions\StoreMicrositeAction;
use App\Domain\Microsite\Actions\UpdateMicrositeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Microsite\StoreMicrositeRequest;
use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MicrositeController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $this->authorize(Abilities::VIEW_ANY->value, Microsite::class);
        $microsites = Microsite::withCategory()->get();

        return Inertia::render('Microsites/Index', compact('microsites'));
    }

    public function create(): Response
    {
        $this->authorize(Abilities::CREATE->value   , Microsite::class);
        $arrayConstants = $this->getCommonData();

        return Inertia::render('Microsites/Create', $arrayConstants);
    }

    public function store(StoreMicrositeRequest $request, StoreMicrositeAction $storeAction): RedirectResponse
    {
        $this->authorize(Abilities::STORE->value, Microsite::class);
        $storeAction->execute($request->validated());

        return to_route('microsites.index');
    }

    public function show(string $id): Response
    {
        $this->authorize(Abilities::VIEW->value, Microsite::class);
        $microsite = Microsite::withCategory($id)->firstOrFail()->toArray();

        return Inertia::render('Microsites/Show', compact('microsite'));
    }

    public function edit(string $id): Response
    {
        $this->authorize(Abilities::EDIT->value, Microsite::class);
        $microsite = Microsite::findOrFail($id);
        $arrayConstants = $this->getCommonData();

        return Inertia::render('Microsites/Edit', compact('microsite', 'arrayConstants'));
    }

    public function update(UpdateMicrositeRequest $request, string $id, UpdateMicrositeAction $updateAction): RedirectResponse
    {
        $this->authorize(Abilities::UPDATE->value, Microsite::class);
        $updateAction->execute($id, $request->validated());

        return to_route('microsites.index');
    }

    public function destroy(int $id,DestroyMicrositeAction $destroyAction): RedirectResponse
    {
        $this->authorize(Abilities::DELETE->value, Microsite::class);
        $destroyAction->execute($id);

        return to_route('microsites.index');
    }

    public function welcomeIndex(): Response
    {
        $microsites = Microsite::withCategory()->get();

        return Inertia::render('Welcome', compact('microsites'));

    }
    public function paymentForm(): Response
    {
        return Inertia::render('PaymentForm');
    }

    private function getCommonData(): array
    {
        return [
            'documentTypes' => DocumentTypes::getDocumentTypes(),
            'micrositesTypes' => MicrositesTypes::getMicrositesTypes(),
            'currencyTypes' => CurrencyTypes::getCurrencyType(),
            'categories' => Category::select('id', 'name')->get(),
        ];
    }
}
