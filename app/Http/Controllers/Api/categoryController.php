<?php

namespace App\Http\Controllers\Api;

use App\Domain\Category\Actions\StoreCategoryAction;
use App\Domain\Category\Actions\UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Repositories\EloquentCategoryReponsitory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class categoryController extends Controller
{
    use AuthorizesRequests;
    private $categoryRepository;

    public function __construct(EloquentCategoryReponsitory $repository)
    {
        $this->categoryRepository = $repository;
    }

    public function index(): Response
    {
        $this->authorize('viewAny', Category::class);
        return Inertia::render('Categories/Index', [
            'categories' => $this->categoryRepository->getAllWithCategories()
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Category::class);
        return Inertia::render('Categories/Create');
    }

    public function store(StoreCategoryRequest $request,StoreCategoryAction $storeAction): RedirectResponse
    {
        $this->authorize('store', Category::class);
        return $storeAction->execute($request->validated());
    }

    public function edit(string $id): Response
    {
        $this->authorize('edit', Category::class);
        $category = $this->categoryRepository->find($id);
        return Inertia::render('Categories/Edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, string $id, UpdateCategoryAction $updateAction): RedirectResponse
    {
        $this->authorize('update', Category::class);
        return  $updateAction->execute($id, $request->validated());
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->authorize('delete', Category::class);
        $this->categoryRepository->delete($id);
        return to_route('categories.index');
    }
}
