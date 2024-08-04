<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Abilities;
use App\Domain\Category\Actions\DestroyCategoryAction;
use App\Domain\Category\Actions\StoreCategoryAction;
use App\Domain\Category\Actions\UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Infrastructure\Persistence\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $this->authorize(Abilities::VIEW_ANY->value, Category::class);

        return Inertia::render('Categories/Index', [
            'categories' => Category::select(['id', 'name', 'description'])->orderby('name', 'asc')->paginate(5)
        ]);
    }

    public function create(): Response
    {
        $this->authorize(Abilities::CREATE->value, Category::class);

        return Inertia::render('Categories/Create');
    }

    public function store(StoreCategoryRequest $request, StoreCategoryAction $storeAction): RedirectResponse
    {
        $this->authorize(Abilities::STORE->value, Category::class);
        $storeAction->execute($request->validated());

        return to_route('categories.index');
    }

    public function edit(string $id): Response
    {
        $this->authorize(Abilities::EDIT->value, Category::class);
        $category = Category::find($id);

        return Inertia::render('Categories/Edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, string $id, UpdateCategoryAction $updateAction): RedirectResponse
    {
        $this->authorize(Abilities::UPDATE->value, Category::class);
        $updateAction->execute($id, $request->validated());

        return to_route('categories.index');
    }

    public function destroy(string $id, DestroyCategoryAction $destroyAction): RedirectResponse
    {
        $this->authorize(Abilities::DELETE->value, Category::class);
        $destroyAction->execute($id);

        return to_route('categories.index');
    }
}
