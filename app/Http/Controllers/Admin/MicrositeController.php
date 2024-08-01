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
use App\Infrastructure\Persistence\Models\User;
use App\ViewModels\Microsites\MicrositeIndexViewModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class MicrositeController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize(Abilities::VIEW_ANY->value, Microsite::class);
        $microsites = (new MicrositeIndexViewModel($user));

        return Inertia::render('Microsites/Index', [
          'microsites'  => $microsites
        ]);
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

        return to_route('microsites.index')->with('success', 'User created.');
    }

    public function show(string $id): Response
    {
        $this->authorize(Abilities::VIEW->value, Microsite::class);
        $microsite = Microsite::withCategory($id)->firstOrFail()->toArray();
        $arrayConstants = $this->getCommonData();

        return Inertia::render('Microsites/Show', compact('microsite','arrayConstants'));
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
        $microsites = Microsite::AllWithCategories()->get();

        return Inertia::render('Welcome', compact('microsites'));

    }
    public function paymentForm(string $slug): Response
    {
        $microsite = Microsite::where('slug', $slug)->with('form')->firstOrFail();
        $arrayConstants = $this->getCommonData();

        return Inertia::render('Form/PaymentForm', compact('microsite','arrayConstants'));
    }

    private function getCommonData(): array
    {
        return [
            'documentTypes' => DocumentTypes::getDocumentTypes(),
            'micrositesTypes' => MicrositesTypes::getMicrositesTypes(),
            'currencyTypes' => CurrencyTypes::getCurrencyType(),
            'categories' => Category::select('id', 'name')->get(),
            'users' => User::role('admin')->select('id', 'name')->get(),
        ];
    }
}
